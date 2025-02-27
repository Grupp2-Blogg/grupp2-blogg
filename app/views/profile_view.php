<?php

declare(strict_types=1);

function normal_display($user)
{
    echo "<ul> ";
    echo "<li>AnvändarID: " . "[" . $user['id'] . "]" . "</li>";
    echo "<li>Användarnamn: " . $user['username'] . "</li>";
    echo "<li>Email: " . $user['email'] . "</li>";
    echo "<li>Lösenord: </li>";
    echo "<li>Förnamn: " . $user['firstname'] . "</li>";
    echo "<li>Efternamn: " . $user['lastname'] . "</li>";
    echo "<li>Kön: " . $user['gender'] . "</li>";
    echo "<li>Födelseår: " . $user['birthyear'] . "</li>";
    echo "<li>Konto skapat: " . $user['created_at'] . "</li>";
    echo "</ul> ";
}

function edit_display($user)
{
    require_once '../includes/editform.inc.php';
}
