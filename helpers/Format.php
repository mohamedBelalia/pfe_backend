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

            $ParentParams = explode("&" , $paramiters);

            $ParamsKeyValueTab = array();

            foreach($ParentParams as $param){
                $childParams =  self::explodeByEquals($param);
                if($childParams != null){
                    $ParamsKeyValueTab[$childParams[0]] = $childParams[1] ;
                }
            }

            return $ParamsKeyValueTab ;
    }


    public static function explodeByEquals($keyValue){
        $passedParamiters = explode("=" , $keyValue);

        if($passedParamiters[0] == "id" || $passedParamiters[0] == "top" || $passedParamiters[0] == "workerId" || 
            $passedParamiters[0] == "images" || $passedParamiters[0] == "rateOf"){
            $passedParamiterValue = $passedParamiters[1]; 
        }
        else if($passedParamiters[0] == "filter"){
            $passedParamiterValue = self::formatFilterParamiters($passedParamiters[1]);
        }
        else{
            return null ;
        }

    
        $paramiterKeyValue = [$passedParamiters[0] , $passedParamiterValue ];

        return $paramiterKeyValue ;
    }

}

?>

