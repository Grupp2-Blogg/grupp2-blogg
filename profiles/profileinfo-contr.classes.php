

<?php

//    class ProfileInfoContr extends ProfileInfo
//    {

//     private $userId;
//     private $username;  //Den som storar användarnamnen ForenKey

//         public function __construct($userId, $username)
//         {
//             $this->userId = $userId;
//             $this->username = $username;
//         }

//         public function defaultProfileInfo()
//         {
//             $profileAbout = "Skriv lite här om dig själv, super kul thihi ";
//             $profileTitle = "Tjenare! Jag är " . $this->username;
//             $profileText = "Välkommen till min profilsida din lilla lurifax.";

//             $this->setProfilInfo($profileAbout, $profileTitle, $profileText, $this->userId);
//         }


//         public function updateProfileInfo($about, $introTitle, $introText)
//         {           //hanterar error
//             if($this->emptyInputCheck($about, $introTitle, $introText) == true) 
//             {
//                 header("location: ../profilesettings.php?error=emptyinput");
//                 exit();
//             }

//             //uppdatera profilinfo

//             $this->setNewProfilInfo($about, $introTitle, $introText, $this->userId);


//         }

//         private function emptyInputCheck($about, $introTitle, $introText) 
//         {
//             $result = 0;
//             if(empty($about) || empty($introTitle) || empty($introText))
//             {
//                 $result = true;
//             }         
//               else
//             {
//                 $result = false;
//             }
//             return $result;

//         }


    
        
        
        
        
        
        
        //Härifrån stökar jag till det
        
        
       // class Profile extends Dbh
       //  {
                // protected function getUserId($username)
                // {
                //          $stmt = $this->connect()->prepare('SELECT users_id from profiles WHERE username = ?;');
                
                //          if($stmt->execute(array($username)))
                //          {
                //                  $stmt = null;
                //                  header("location: profile.php?error=stmtfailed");
                //                  exit();
                //              }
                    
                //              if($stmt->rowCount() == 0)
                //              {
                //                      $stmt = null;
                //                      header("location: profile.php?error=profilenotfound");
                //                      exit();
                        
                        
                        
                //                  }
                        
                //                  $profileData = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                //                  return $profileData;
                        
                        
                                                
                        
                //              }
                        
                 //       }
                        
                        
                        // public function fetchUserId($username) 
                        // {
                        //     $userId = $this->getUserId($username);
                        //     return $userId[0]["users_id"];
                        // }
                    
                    
                    
 //  } 