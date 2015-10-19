<?php
/**
 * Created by PhpStorm.
 * User: Don
 * Date: 5/13/2015
 * Time: 9:13 PM
 */

//Fill an array with all items(filenames) from a directory
$handle = opendir('./Photos/');        // . represents the current directory, while .. the parent directory.
while (false !== ($file = readdir($handle))) { //readdir() returns the entry name on success or FALSE on failure.
//    $files[] = $file;          //add the read in file name in the files[] array
    if ($file != "." && $file != "..") {  //this prevents current and previous directory being read in
        $dirFiles[$file] = filesize("./Photos/$file")." ".filectime("./Photos/$file");
    }
}
closedir($handle);

//print_r($dirFiles);  //display the array with files read in.
echo PHP_EOL;

//Store - Write Filenames to a text file before you rewrite them
$Handle = fopen("./Store/"."FileNames.txt", 'a');
foreach ($dirFiles as $file => $value) {
//    $data = $value."\n";                //put each entry on a new line
    $data = $file." ".$value."\n";        //put each filename, size, and timestamp on a separate line.
                                          //when using this data later remove the invisible \n!
    fwrite($Handle, $data);
}

print "Data Added to ./Store/Filenames.txt \n";
fclose($Handle);

asort($dirFiles);    //sort the contents of the array alphabetically, even though they are read in alphabetically
print_r($dirFiles);    //display the sorted files that have been read in, order is the same as Linux sorts them too
//var_dump($dirFiles);

//Create the new filenames and rename the files in the directory
$i = 1;             //this variable is used as the numeric value for filenames.
foreach($dirFiles as $file => $value)
{
    $fileParts = pathinfo("./Photos/.$file"); //returns an associative array containing dirname, basename, extension, filename.
//    print_r($fileParts).PHP_EOL;

    $fileExt = $fileParts['extension']; //stores the extension retrieved above for each file.
//    echo $fileExt.PHP_EOL.PHP_EOL;

    $newName = $i . '.' . $fileExt;       //create the new numeric file name
          //the second time this runs files get deleted probably because they get the same name.

    rename('./Photos/'.$file, './Photos/'.$newName);  //actually rename the file

    $i++;   //increment the filename
}
print "Files have been renamed in ./Photos/ to have numeric filenames \n";
