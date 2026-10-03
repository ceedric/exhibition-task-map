<?php
// Exhibition Task Map — tiny shared-state API. No password: anyone with the
// URL can read/write, same trust model as pasting a code around by hand.
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if($_SERVER['REQUEST_METHOD'] === 'OPTIONS'){ exit; }

$dataFile   = __DIR__ . '/exhibition_data.json';
$historyDir = __DIR__ . '/history';
if(!is_dir($historyDir)) @mkdir($historyDir, 0755, true);

$method = $_SERVER['REQUEST_METHOD'];

if($method === 'GET'){

  // list recent snapshots: api.php?history=1
  if(isset($_GET['history'])){
    $files = glob($historyDir . '/*.json');
    usort($files, function($a,$b){ return filemtime($b) - filemtime($a); });
    $out = array_map(function($f){
      return [
        'file'  => basename($f),
        'mtime' => date('Y-m-d H:i:s', filemtime($f)),
      ];
    }, array_slice($files, 0, 100));
    echo json_encode($out);
    exit;
  }

  // fetch one old snapshot's content: api.php?restore=<filename>
  if(isset($_GET['restore'])){
    $name = basename($_GET['restore']); // strip any path — only filenames in history/ are ever readable
    $path = $historyDir . '/' . $name;
    if(preg_match('/^[\w\-\.]+\.json$/', $name) && file_exists($path)){
      readfile($path);
    } else {
      http_response_code(404);
      echo json_encode(['error' => 'snapshot not found']);
    }
    exit;
  }

  // default: current state
  if(file_exists($dataFile)){
    readfile($dataFile);
  } else {
    echo json_encode(['roster' => [], 'tasks' => [], 'collapsed' => [], 'calendarHidden' => [], 'v' => 0]);
  }
  exit;
}

if($method === 'POST'){
  $raw = file_get_contents('php://input');
  $decoded = json_decode($raw, true);
  if($decoded === null || !isset($decoded['tasks'])){
    http_response_code(400);
    echo json_encode(['error' => 'invalid payload']);
    exit;
  }

  // write the live file
  file_put_contents($dataFile, $raw);

  // drop a timestamped snapshot into history/ for the revert feature
  $stamp = date('Y-m-d_H-i-s') . '_' . substr(md5($raw . microtime()), 0, 6) . '.json';
  file_put_contents($historyDir . '/' . $stamp, $raw);

  // keep history from growing forever — cap at the most recent 200 snapshots
  $files = glob($historyDir . '/*.json');
  if(count($files) > 200){
    usort($files, function($a,$b){ return filemtime($a) - filemtime($b); });
    $toDelete = array_slice($files, 0, count($files) - 200);
    foreach($toDelete as $f) @unlink($f);
  }

  echo json_encode(['ok' => true, 'file' => $stamp]);
  exit;
}

http_response_code(405);
echo json_encode(['error' => 'method not allowed']);
