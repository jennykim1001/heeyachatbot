<?php
// 유메타랩(프롬프트 엔지니어 코리아)의 챗봇 개발 예제 소스입니다.
// https://promptengineer.kr / 유메타랩(주)
// 문의: seo@seowan.net / edu@yumeta.kr

$ch = curl_init();
$url = 'https://api.openai.com/v1/chat/completions';
$api_key = 'api키를 여기에 입력하세요';

list($prompt, $old_prompt, $old_result) = explode("/////", $_POST['text']);

$post_data = [
    "model" => "gpt-3.5-turbo-16k",
    "messages" => [
        [
            "role" => "system",
            "content" => "안녕하세요. 당신의 이름은 '테스트봇1'이며, 당신은 유메타랩이 개발했습니다. 당신의 이름은 '테스트봇1'임을 명심하세요."
        ],
        [
            "role" => "user",
            "content" => $old_prompt
        ],
        [
            "role" => "assistant",
            "content" => $old_result
        ],
        [
            "role" => "user",
            "content" => $prompt
        ]
    ],
    "max_tokens" => 1000,
    "temperature" => 0.7
];

$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $api_key
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
}
curl_close($ch);

$response = json_decode($result);
$message_content = var_export($response->choices[0]->message->content, true);
$message = trim($message_content, "'");

$message = str_replace("\n", "<br/>", $message);

if (strpos($message, "NULL") !== false) {
    echo "<font color=red>서버에 오류가 발생했습니다. 페이지를 새로고침해주세요. $result </font>";
} else {
    echo stripslashes($message);
}
?>
