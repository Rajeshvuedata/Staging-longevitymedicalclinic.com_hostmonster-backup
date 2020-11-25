
<?php

$txt4 = $_POST["password"];
If ($txt4 === "Chumba!Wumba43")
    {
    Echo $txt4;
    $myfile = fopen("LMCEvent.php", "w") or die("Unable to open file!");
    $txt1 = "<?php header('Location: ";
    $txt2 = $_POST["url"];
    $txt3 = "'); ?>";
    fwrite($myfile, $txt1.$txt2.$txt3);
    fclose($myfile); 
    header('Location: '.$txt2); 
    }
Else{
    Echo nl2br("Password incorrect!\n Uploading a Virus to destroy your machine.\n Calling the FBI!\n And dispatching ninja assasins to murder puppies.\n Be ashamed! \n");
    }
?>