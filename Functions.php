<?php
function getParam($key)
{
  return !empty($_GET[$key]) ? $_GET[$key] : '';
}

function getPostParam($key)
{
  $value = !empty($_POST[$key]) ? $_POST[$key] : null;
  if ($value == null) {
    $value = file_get_contents('php://input');
    $value = json_decode($value, true);
    $value = !empty($value[$key]) ? $value[$key] : '';
  }
  return $value;
}

function encriptId($id)
{
  return base64_encode(base64_encode(json_encode(['id' => $id, 'timestamp' => time()])));
}

// function decriptId($id)
// {
//   return base64_decode(base64_decode(json_decode([$id])));
//   // return json_encode()
// }

 // function decId(hashId) {
  //   return JSON.parse(atob(atob(hashId))).id
  // }
