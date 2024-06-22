<?php 

class AuhtenticationModel{
    private $dbConnection ;
    private $authKey ;

    public function __construct($authKeyPassed){
        $this->dbConnection = DbConnection::dbCon() ;
        $this->authKey = $authKeyPassed ;
    }

    // signup
    public function signupUser($userData){
        if($this->dbConnection != null){
            if(strlen($userData["firstName"]) > 1 && strlen($userData["phoneNumber"]) > 8 && strlen($userData["password"]) > 5 
                && strlen($userData["ville"]) > 0 && count($userData["profession"]) > 0 ){

                $firstName = $userData["firstName"];
                $lastName = isset($userData['lastName']) ? $userData['lastName'] : '';
                $phoneNumber = $userData["phoneNumber"] ;
                $password = $userData["password"] ;
                $ville = $userData["ville"] ;
                $professions = $userData["profession"] ;

                if($this->isUserNotExist($phoneNumber)){

                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    
                    $this->dbConnection->begin_transaction();
    
                    try {
                        $queryIsertingWorker = "INSERT INTO ouvriers(nomOuvrier ,prenomOuvrier , phone , motDePasse , ville, imgProfile) VALUES (?,?,?,?,?,?)";

                        $imgPath = "defaultUserImage.png" ;
                        
                        $stmtInsertingWorker = $this->dbConnection->prepare($queryIsertingWorker);
                        $stmtInsertingWorker->bind_param("ssssss", $lastName, $firstName, $phoneNumber , $hashedPassword , $ville , $imgPath);
                        $stmtInsertingWorker->execute();
            
                        $idWorker = $stmtInsertingWorker->insert_id;
            
                        // Insertion dans la table "ouvriers_maitrisent_professions"
                        $queryInsertInProjetImages = "INSERT INTO ouvriers_maitrisent_professions(idProfession, idOuvrier) VALUES (?,?)";
                        $stmtCommandProjetImages = $this->dbConnection->prepare($queryInsertInProjetImages);
            
                        foreach ($professions as $idProfession) {
                            $stmtCommandProjetImages->bind_param("ii", $idProfession, $idWorker);
                            if (!$stmtCommandProjetImages->execute()) {
                                throw new Exception("Error executing query");
                            }
                        }
                        

                        $payloadData = [
                            "id" => $idWorker,
                            "phoneNumber" => $phoneNumber,
                            "firstName" => $firstName,
                            "imgPath" => "defaultUserImage.png"
                        ];

                        $token = AuhtenticationModel::generateJWT($payloadData , $this->authKey) ;

                        $queryToken = "UPDATE ouvriers SET token = '$token' WHERE idOuvrier = $idWorker;" ;
                        $this->dbConnection->query($queryToken);

                        $this->dbConnection->commit();

                        return $token ;

                    } catch (Exception $e) {
                        $this->dbConnection->rollback();
                        return "something_wrong";
                    }
                }
                else{
                    return "already_exist";
                }

            }
            else{
                return "uncomplate_data";
            }
        }
    }

    // login
    public function login($userData){
        if($this->dbConnection != null){
            if(strlen($userData["phone"]) > 8 && strlen($userData["pwd"]) > 1 ){
                $phone = $userData["phone"] ;
                $password = $userData["pwd"] ;

                $query = "SELECT token , motDePasse FROM ouvriers WHERE phone = ?;";
                $stmtLogin = $this->dbConnection->prepare($query);
                $stmtLogin->bind_param("s" , $phone);
                $stmtLogin->execute();

                $result = $stmtLogin->get_result();
                if ($result->num_rows == 1){
                    $row = $result->fetch_assoc();
                    $token = $row['token'];
                    $storedPwd = $row['motDePasse'];

                    if(password_verify($password , $storedPwd)){
                        return $token ;
                    }
                    else{
                        return "not_found";
                    }
                }
                else{
                    return "not_found";
                }
            }
            else{
                return "uncomplate_data";
            }
        }
        else{
            return "connection_error";
        }
    }

    // to check if the unique phone number already exist in the database
    private function isUserNotExist($phoneNumber){
        $query = "SELECT * FROM ouvriers WHERE phone = '$phoneNumber';";

        $result = $this->dbConnection->query($query);

        if($result->num_rows == 0){
            return true;
        }
        return false ;
    }


    // ############# creating the JWT

    // encoding to base64
    public static function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    // decoding to base64
    public static function base64UrlDecode($data) {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    public static function createJWT($header, $payload, $secret) {

        // header of JWT
        $headerEncoded = AuhtenticationModel::base64UrlEncode(json_encode($header));
        
        // payload of JWT
        $payloadEncoded = AuhtenticationModel::base64UrlEncode(json_encode($payload));
    
        // signature of JWT
        $signature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $secret, true);
        
        // encoding signature
        $signatureEncoded = AuhtenticationModel::base64UrlEncode($signature);
        
        // final JWT
        $jwt = "$headerEncoded.$payloadEncoded.$signatureEncoded";
    
        return $jwt;
    }

    public static function generateJWT($payload , $authKey){
        // header
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];

        return AuhtenticationModel::createJWT($header , $payload , $authKey);
    }


    // verifingJWT the JWT
    public static function verifingJWT($jwt, $secret) {
        list($headerEncoded, $payloadEncoded, $signatureEncoded) = explode('.', $jwt);
    
        // Decod and payload
        $payload = json_decode(AuhtenticationModel::base64UrlDecode($payloadEncoded), true);
    
        // Verify the signature
        $validSignature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $secret, true);
        $validSignatureEncoded = AuhtenticationModel::base64UrlEncode($validSignature);
    
        if ($signatureEncoded !== $validSignatureEncoded) {
            return "not_valid" ;
        }
    
        return $payload;
    }
    


}


/*
eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MjQsInBob25lTnVtYmVyIjoiMDc3Nzc4Nzc3NyIsImZpcnN0TmFtZSI6IktoYWxpZCAxIiwicHdkIjoiMTMyQUVSIzQxIn0.V-Nv-oEK-SU1ESJbB2soCSotVRAewF_xBIkFZekLyMY
*/

?>