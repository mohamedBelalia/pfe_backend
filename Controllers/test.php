<?php   

    $dotEnv = new DotEnvEnvironment();
    $dotEnv->load();

    echo getenv("AUTH_SECRET_KEY");

?>