<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? 'KOSONGANNUL';
    $endPoint = $_POST['endPoint'] ?? 'KOSONGAN';
    $username = $_POST['username'] ?? 'iptInstall1zz';
    $pass = $_POST['pass'] ?? 'NOC%20TELEGLOBALzzz';

    $url = "https://$endPoint/api/getsensordetails.json?id=$id&username=$username&password=$pass";

    // Inisialisasi cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // Abaikan validasi SSL
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // Abaikan validasi SSL

    // Ambil respons dari API
    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        echo json_encode(['error' => "cURL Error: $error"]);
        exit;
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Validasi status HTTP
    if ($httpCode === 200) {
        $parsedData = json_decode($response, true);
        $data = [
            'sensordata' => [
                'name' => $parsedData['sensordata']['name'] ?? 'Unknown',
                'value' => $parsedData['sensordata']['lastvalue'] ?? 'Unknown',
            ],
        ];
    } else {
        $data = ['error' => "HTTP Error: $httpCode"];
    }

    echo json_encode($data);
}
