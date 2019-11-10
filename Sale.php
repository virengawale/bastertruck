<?php
// 

class Sale
{
    private $file_name = NULL;

    public function __construct($file_name='report.csv')
    {
        // Constructor initialise output file name
        $this->file_name = $file_name;
    }

    private function is_weekend($date)
    {
	// check date is weekend
        $day = date('D',strtotime($date));
        if($day=='Sat' || $day=='Sun')
        {
            return true;
        }
        return false;
    }

    private function create_csv($data){
	// create output file 
	    $file = fopen("$this->file_name","w");
	    fputcsv($file, array("Month Name", "Salary Date", "Bonus Date"));
	    foreach ($data as $line) {
		    fputcsv($file, $line);
	    }
	    fclose($file);
    }

    public function generate_csv_report()
    {

        $month = date('m');
        $year = date('Y');
        $report_arr = array();
    
        for ($month ; $month <= 12 ; $month++ )
        {
            $date = new DateTime("$year-$month-01");
            $date->modify('last day of this month');
            $salary_date = $date->format('Y-m-d');
            
            if( self::is_weekend($salary_date))
            {
                $date->modify('last friday of this month');
                $salary_date = $date->format('Y-m-d');
            }

            $date = new DateTime("$year-$month-15");
            $bonus_date = $date->format('Y-m-d');
        
            if( self::is_weekend($bonus_date) )
            {
                $date->modify('next wednesday');
                $bonus_date = $date->format('Y-m-d');
            }

            $month_name = $date->format('F');
            $salary_bonus_date_of_month = array("$month_name","$salary_date","$bonus_date");
            array_push($report_arr,$salary_bonus_date_of_month);

        } // for loop for month
	self::create_csv($report_arr);
    } // function generate_csv_report

}//class

$sale_obj = new Sale($argv[1]);
$sale_obj->generate_csv_report();

?>
