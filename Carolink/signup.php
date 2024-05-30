<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up • Carolink</title>
<link rel="icon" type="image/x-icon" href="images/favicon.ico">
<link rel="stylesheet" href="SignStyle.css">
</head>
<body>

<div class="title">
<img src="images/carolink.png" alt="Logo" class="logo">
<p>Hey Carolinian! Create your Carolink account now!</p>
</div>

<div class="container">
<h1>Sign Up</h1>
<form id="signupForm" action="create_account.php" method="POST" enctype="multipart/form-data">

<div class="input-group">
<div class="form-group">
<input type="text" id="firstName" name="firstName" placeholder="First Name" required>
</div>

<div class="form-group">
<input type="text" id="lastName" name="lastName" placeholder="Last Name" required>
</div>
</div>

<div class="input-group">
<div class="form-group">
<input type="email" id="email2" name="email2" placeholder="USC Email" required>
</div>
</div>

<div class="input-group">
<div class="form-group">
<input type="password" id="password" name="password" placeholder="Password" required>
</div>
</div>

<div class="input-group">
<div class="form-group">
<input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
</div>
</div>

<div class="input-group">
<div class="form-group">
<input type="date" id="birthdate" name="birthdate" required>
</div>

<div class="form-group">
<select id="gender" name="gender" required>
<option value="" disabled selected>Gender</option>
<option value="male">Male</option>
<option value="female">Female</option>
<option value="other">Other</option>
</select>
</div>
</div>

<div class="input-group">
<div class="form-group">
<p class="inline-paragraph">Upload a PDF file of current Study Load from ISMIS and USC Student ID</p>
<input type="file" id="pdfFile" name="pdfFile" accept=".pdf" required multiple>
</div>
</div>

<div class="form-group">
<input type="checkbox" id="terms" name="terms" required>
<label for="terms" class="checkbox-label">I agree to the <a class="a1" onclick="openModal(event)">Terms and Conditions</a></label>
</div>

<button type="submit">Sign Up</button>
</form>
<p class="p1">Already have an account? <a href="login.html">Login here</a></p>
</div>

<!-- Modal -->
<div id="myModal" class="modal">
<div class="modal-content">
<span class="close" onclick="closeModal()">&times;</span>
<h2>Terms and Conditions</h2>
<p>By creating an account on Carolink, you agree to the following terms and conditions:</p>
<p class="pp">Compliance with Laws and Regulations</p>
<ul>
<li>You will use this service in strict compliance with all applicable laws and regulations. This includes but is not limited to laws governing data protection, privacy, intellectual property, and online conduct.</li>
</ul>

<p class="pp">Account Security</p>
<ul>
<li>You are solely responsible for maintaining the confidentiality of your account information and password. You agree to take all necessary measures to prevent unauthorized access to your account and to promptly notify us of any unauthorized use or security breaches.</li>
</ul>

<p class="pp">Termination of Accounts</p>
<ul>
<li>Carolink reserves the right to terminate accounts that violate our policies or misuse our services. This includes, but is not limited to, engaging in unlawful activities, violating the rights of others, or using the platform for spam or malicious purposes.</li>
</ul>

<p class="pp">Privacy Policy</p>
<ul>
<li>Your personal information will be handled in accordance with our Privacy Policy. We are committed to protecting your privacy and safeguarding your personal data. Please review our Privacy Policy for more information on how we collect, use, and disclose your information.</li>
</ul>

<button type="button" class="accept-button">Accept</button>
</div>
</div>

<script src="CreateAcc.js"></script>
</body>
</html>
