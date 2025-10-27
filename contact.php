<?php
/**
 * DC Robotic School - Contact Form Handler
 * File: contact.php
 * Purpose: Process contact form and redirect to WhatsApp
 */

// Nomor WhatsApp tujuan (ganti dengan nomor yang sesuai)
$whatsapp_number = '62895366780417'; // Format: 62 untuk Indonesia

// Cek apakah form dikirim dengan method POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Ambil dan sanitasi data dari form
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $phone = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : '';
    $subject = isset($_POST['subject']) ? htmlspecialchars(trim($_POST['subject'])) : '';
    $budget = isset($_POST['budget']) ? htmlspecialchars(trim($_POST['budget'])) : 'Tidak disebutkan';
    $message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : '';
    
    // Validasi data wajib
    if (empty($name) || empty($email) || empty($phone) || empty($subject) || empty($message)) {
        // Redirect kembali ke form dengan error
        header('Location: kontak.html?error=missing_fields');
        exit;
    }
    
    // Validasi format email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: kontak.html?error=invalid_email');
        exit;
    }
    
    // Format pesan untuk WhatsApp
    $whatsapp_message = "Halo DC Robotic School! 👋\n\n";
    $whatsapp_message .= "Saya ingin mendaftar:\n\n";
    $whatsapp_message .= "*Nama:* $name\n";
    $whatsapp_message .= "*Email:* $email\n";
    $whatsapp_message .= "*Telepon:* $phone\n";
    $whatsapp_message .= "*Subject:* $subject\n";
    $whatsapp_message .= "*Budget:* $budget\n\n";
    $whatsapp_message .= "*Pesan:*\n$message\n\n";
    $whatsapp_message .= "Terima kasih!";
    
    // Encode pesan untuk URL
    $encoded_message = urlencode($whatsapp_message);
    
    // Buat URL WhatsApp
    $whatsapp_url = "https://wa.me/$whatsapp_number?text=$encoded_message";
    
    // Optional: Simpan data ke database atau file log
    // Contoh simpan ke file log (uncomment jika diperlukan):
    /*
    $log_file = 'contact_logs.txt';
    $log_entry = date('Y-m-d H:i:s') . " | $name | $email | $phone | $subject\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND);
    */
    
    // Optional: Kirim email notifikasi ke admin
    // (uncomment dan sesuaikan jika diperlukan):
    /*
    $admin_email = 'admin@dcrobotic.sch.id';
    $email_subject = "Pesan Baru dari $fullName";
    $email_body = strip_tags($whatsapp_message);
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    mail($admin_email, $email_subject, $email_body, $headers);
    */
    
    // Redirect ke WhatsApp
    header("Location: $whatsapp_url");
    exit;
    
} else {
    // Jika bukan POST request, redirect ke halaman kontak
    header('Location: kontak.html');
    exit;
}
?>