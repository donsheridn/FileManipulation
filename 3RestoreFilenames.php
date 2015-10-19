<?php
/**
 * Created by PhpStorm.
 * User: Don
 * Date: 5/16/2015
 * Time: 8:50 PM
 */

//Read in the original filenames from ./Store/FileNames.txt
//$contents = file_get_contents("./Store/FileNames.txt");   //reads the file contents into a string

//echo $contents.PHP_EOL;

//$filenames = explode(" ", $contents);   //now puts filenames in separate cells
//print_r($filenames).PHP_EOL.PHP_EOL;
//
//echo $filenames[0].PHP_EOL;
//echo $filenames[1].PHP_EOL;
////echo $filenames[1];

//Read in the backed-up original filenames, filesizes, and timestamps
$handle = @fopen("./Store/FileNames.txt", "r");
if ($handle) {
    while (($buffer = fgets($handle)) !== false) {  //fgets reads a line + returns a string with filename,
                                                    //filesize, timestamp
        $trimmedBuffer = rtrim($buffer);      //it is necessary to remove the invisible \n character at
                                              //the end of every line
//        echo $buffer;
        $filedets = explode(" ",$trimmedBuffer);
//        $filedetails[] = explode(" ",$buffer); //puts filename, filesize, and timestamp in separate cells
//        $filedetails[$filedets[0]] = explode(" ",$buffer);
        $filedetails[] = $filedets[0]." ".$filedets[1]." ".$filedets[2];
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
}
print_r($filedetails).PHP_EOL;


//Read in the current files in the directory, so they can be used for renaming below
$handle = opendir("./Photos");        // . represents the current directory, while .. the parent directory.
while (false !== ($filename = readdir($handle))) { //readdir() returns the entry name on success or FALSE on failure.
//    $files[] = $filename;          //add the read in file name in the files[] array
    if ($filename != "." && $filename != "..") {  //this prevents current and previous directory being read in

        $fileSize = filesize("./Photos/$filename"); //gets the filesize for each file
        $fileTime = filectime("./Photos/$filename"); //gets the timestamp for each file

        $dirFiles[$filename] = $fileSize." ".$fileTime; //store the read in filedetails in an array
    }
}
closedir($handle);
print_r($dirFiles);


//Actually rename the files back to what they where.
foreach ($dirFiles as $filename => $sizetime) {

    $Arraysizetime = explode(" ",$sizetime);       //explode a string into an array
    $fsize = $Arraysizetime[0];
    $ftime = $Arraysizetime[1];

    for ($i=0, $j=count($filedetails); $i < $j; $i++){

        $namesizetime = explode(" ",$filedetails[$i]);   //explode string into array

        $name1 = $namesizetime[0];
        $size1 = $namesizetime[1];
        $time1 = $namesizetime[2];

        if ($fsize === $size1 && $ftime === $time1) {
            echo "time1 is".$time1."while ftime is ".$ftime."nothing else after".PHP_EOL;
            $fileParts = pathinfo("./Photos/.$filename"); //returns an associative array containing dirname, basename, extension, filename.
            //    print_r($fileParts).PHP_EOL;

            $fileExt = $fileParts['extension']; //stores the extension retrieved above for each file.
            //    echo $fileExt.PHP_EOL.PHP_EOL;

            $newName = $name1; //.'.'.$fileExt;       //create the new numeric file name
            //the second time this runs files get deleted probably because they get the same name.

            rename('./Photos/'.$filename, './Photos/'.$newName);  //actually rename the file
            echo ("Just renamed ./Photos/.$filename with $newName \n");
        }
    }
}
unlink('./Store/Filenames.txt');    //delete the backed up files after they have been deleted.
print "The filenames have been restored to their originals filenames in ./Photos/ \n";
