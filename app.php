<?php
$count =0;
$res=[];
$first=[];
$second=[];

for($i= $_POST['start']; $i <= $_POST['finish']; $i++){
   $val=$i;
    for ($j=0; $val>0; $j++){

        $res[$j] = $val % 10;
        $val = floor($val / 10);
    }
    $first=array_sum(array_slice($res,0,3));
    $second=array_sum(array_slice($res,3,3));
    if($first==$second){
        $count=$count+1;
    }

}
echo json_encode(['status'=>'success','count'=>$count]);
?>