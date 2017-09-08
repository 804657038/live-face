<?php

$imagek=$_POST['img'];
$image=explode(',',$imagek);

$img=base64_decode($image[1]);
$img_page="img/".time().'.png';


if(file_put_contents($img_page,$img)){
    //$img_page="img/1504521702.png";
    $fp = fopen($img_page, 'rb');
    $content = fread($fp, filesize($img_page)); //二进制数据
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api-cn.faceplusplus.com/facepp/v3/detect",     //输入URL
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array(
            'image_file";filename="image'=>"$content",
            'api_key'=>"lV4J2JSrtLZiMs6WyCo_QQMh3vUGQ19t",
            'api_secret'=>"_sBjPmsJfUIBOksgWccaeEs0Qhgpb9B4",
            'return_landmark'=>1,
            'return_attributes'=>"emotion"
        ),   //输入api_key和api_secret
        CURLOPT_HTTPHEADER => array("cache-control: no-cache",),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    $emotions=[
        'anger'=>'愤怒',
        "disgust"=>'厌恶',
        'fear'=>'恐惧',
        'happiness'=>'高兴',
        'neutral'=>'平静',
        'sadness'=>'伤心',
        'surprise'=>'惊讶'
    ];

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $response=json_decode($response,true);

        $emotion=$response['faces'][0]['attributes']['emotion'];

        $k=array_search(max($emotion),$emotion);

        echo json_encode([
            'code'=>1,
            'msg'=>$emotions[$k]
        ]);;
    }
};

?>