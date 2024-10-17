<!DOCTYPE html>
<html>
<head>
    <title>New Account Created</title>
</head>
<body>
    <h1>Hello {{ $user->name }},</h1>
    <p>Your new account has been created successfully.</p>
    <p>Account Details:</p>
    <ul>
        <li>Account Number: {{ $account->account_number }}</li>
        <li>Account Type: {{ $account->accountType->name }}</li>
        <li>Initial Balance: {{ $account->account_balance }}</li>
    </ul>
    <p>Thank you for choosing our bank!</p>
</body>
</html>
