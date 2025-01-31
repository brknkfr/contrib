<?php
$ret = array();

// Detect APC functions to call.
$apc_fn_name = "";
if(function_exists("apcu_cache_info") && function_exists("apcu_sma_info"))
{
  $apc_fn_name = "apcu";
} elseif (function_exists("apc_cache_info") && function_exists("apc_sma_info"))
{
  $apc_fn_name = "apc";
}

if(!empty($apc_fn_name))
{
  switch ($_GET["act"])
  {
    case "memory":
      $tmp = call_user_func($apc_fn_name . "_sma_info");
      $ret["mem_used"] = $tmp["seg_size"]-$tmp["avail_mem"];
      $ret["mem_avail"] = $tmp["avail_mem"];
      break;
    case "hits":
      $tmp =  call_user_func($apc_fn_name . "_cache_info");
      $ret["num_hits"] = $tmp["num_hits"];
      $ret["num_misses"] = $tmp["num_misses"];
      break;
    case "percents":
      $tmp =  call_user_func($apc_fn_name . "_sma_info");
      $ret["memory"] = 100-(($tmp["avail_mem"] / $tmp["seg_size"])*100);
      $tmp = apcu_cache_info();
      if (($tmp["num_hits"]+$tmp["num_misses"]) > 0)
      {
          $ret["hits"] = ($tmp["num_hits"] / ( $tmp["num_hits"]+$tmp["num_misses"]) ) * 100;
          $ret["misses"] = ($tmp["num_misses"] / ( $tmp["num_hits"]+$tmp["num_misses"]) ) * 100;
      } else {
          $ret["hits"] = 0;
          $ret["misses"] = 0;
      }
      break;
  }

} else {

  switch ($_GET["act"])
  {
    case "memory":
      $ret["mem_size"] = 0;
      $ret["mem_used"] = 0;
      break;
    case "hits":
      $ret["num_hits"] = 0;
      $ret["num_misses"] = 0;
      break;
    case "percents":
      $ret["memory"] = 0;
      $ret["hits"] = 0;
      $ret["misses"] = 0;
      break;
  }
}


  foreach($ret as $key => $val) echo "$key.value $val\n";

?>
