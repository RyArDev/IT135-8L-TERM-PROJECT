<?php 

    class User{ //All Information for the User

        public $userId;
        public $username;
        public $email;
        public $password;
        public $refreshToken;
        public $dateCreated;
        public $roleId;

    }

    class UserProfile{ //All Information for the User Profile

        public $userProfileId;
        public $firstName;
        public $lastName;
        public $birthDate;
        public $address;
        public $gender;
        public $phoneNumber;
        public $jobTitle;
        public $jobDescription;
        public $profileImageName;
        public $profileImageUrl;
        public $profileBannerName;
        public $profileBannerUrl;
        public $description;
        public $userId;

    }

    class UserLogin{ //Necessary Information for Login

        public $username;
        public $password;
        public $refreshToken;

    }

    class UserRegister{ //Necessary Information for Registration

        public $username;
        public $firstName;
        public $lastName;
        public $email;
        public $password;
        public $confirmPassword;
        public $birthDate;
        public $address;
        public $gender;
        public $agreeTerms;

    }

    class UserEdit{ //All Information for the User to Edit

        public $userId;
        public $username;
        public $email;

    }

    class UserAdminEdit{ //All Information for the User to Edit

        public $userId;
        public $username;
        public $email;
        public $roleId;

    }

    class UserProfileEdit{ //All Information for the User Profile to Edit

        public $userProfileId;
        public $firstName;
        public $lastName;
        public $birthDate;
        public $address;
        public $gender;
        public $phoneNumber;
        public $jobTitle;
        public $jobDescription;
        public $profileImageName;
        public $profileImageUrl;
        public $profileBannerName;
        public $profileBannerUrl;
        public $description;
        public $userId;

    }

    class UserEditPassword{ //All Information for the User to Edit

        public $userId;
        public $oldPassword;
        public $newPassword;
        public $confirmNewPassword;

    }

    class UserRefreshSession{ //Necessary Information for Refreshing Sessions

        public $userId;
        public $refreshToken;

    }

?>