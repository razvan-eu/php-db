<?
class Utils {
	public static function readFileLines($file){
        $handle = fopen($file, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $line = trim($line);
                $linia = explode("=", $line,2);
                if (!empty($line)) {
                    define($linia[0], $linia[1]);
                }
            }
        } else {
            // error opening the file.
        }
        fclose($handle);
    }
    public static function lunaCurenta($index) {
        $luni = array(
            'Ianuarie', 'Februarie', 'Martie', 'Aprilie', 'Mai', 'Iunie', 'Iulie', 'August', 'Seprembrie', 'Octombrie', 'Noiembrie', 'Decembrie'
        );
        return $luni[$index-1];
    }
}
