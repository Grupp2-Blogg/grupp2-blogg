<?php

//    class ProfileInfo extends Dbh 
//    {
//         protected function getProfilInfo($userId)
//         {
//             $stmt = $this->connect()->prepare('SELECT * from profiles WHERE user_id = ?;');

//             if($stmt->execute(array($userId)))
//             {
//                 $stmt = null;
//                 header("location: profile.php?error=stmtfailed");
//                 exit();
//             }

//             if($stmt->rowCount() == 0)
//             {
//                 $stmt = null;
//                 header("location: profile.php?error=profilenotfound");
//                 exit();



//             }

//             $profileData = $stmt->fetchAll(PDO::FETCH_ASSOC);

//             return $profileData;




//         }



//         protected function setNewProfilInfo($profileAbout, $profileTitle, $profileText, $userId)
//         {
//             $stmt = $this->connect()->prepare('UPDATE profiles SET profiles_about = ?, profiles_introtitle = ?, profiles_introtext = ? WHERE users_id = ?;');

//             if($stmt->execute(array($profileAbout, $profileTitle, $profileText, $userId)))
//             {
//                 $stmt = null;
//                 header("location: profile.php?error=stmtfailed");
//                 exit();
//             }

//             $stmt = null;

          



//         }



//         protected function setProfilInfo($profileAbout, $profileTitle, $profileText, $userId)
//         {
//             $stmt = $this->connect()->prepare('INSERT INTO profiles (profiles_about, profiles_introtitle, profiles_introtext, users_id) VALUES (?, ?, ?, ?,);');

//             if($stmt->execute(array($profileAbout, $profileTitle, $profileText, $userId)))
//             {
//                 $stmt = null;
//                 header("location: profile.php?error=stmtfailed");
//                 exit();
//             }

//             $stmt = null;

          



//         }



 //  } 