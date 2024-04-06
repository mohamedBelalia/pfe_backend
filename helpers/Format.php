<?php 

class Format{
    public static function formatFilterParamiters($filterString){
        if($filterString == null){
            return null ;
        }

        $filterParamitersArray = explode("," , $filterString);

        $filterKeyValue = array();

        foreach($filterParamitersArray as $critere){
            $critereParts = explode(":" , $critere);

            $filterKeyValue[trim($critereParts[0])] = trim($critereParts[1]);
        }

        return $filterKeyValue ;

    }


    public static function formatPassedParamiters($paramiters){

            $passedParamiters = explode("=" , $paramiters);

            if($passedParamiters[0] == "id" || $passedParamiters[0] == "top" || $passedParamiters[0] == "workerId"  ){
                $passedParamiterValue = $passedParamiters[1]; 
            }
        
            if($passedParamiters[0] == "filter"){
                $passedParamiterValue = self::formatFilterParamiters($passedParamiters[1]);
            }
        
            $paramiterKeyValue = [$passedParamiters[0] , $passedParamiterValue ];

            return $paramiterKeyValue ;
    }

}

?>