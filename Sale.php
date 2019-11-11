<?php
// 1. Take input file name
// 2. Sales Department Salary and bonus Date report
// 3. Salary processed last day of the month if last day is weekend then proccessed salery on last working day of the month
// 4. Bonus is calculated on 15th of the month for the previus month if 15th is weekend then processed bonus on wenesday after 15th
// 5. Create report for remaining year in .csv file( Format :  format month, salary date, bonus dates)
class Sale
{
    private $file_name = null;
    const WEEK_END_DAY = array( 'Sat', 'Sun' );

    public function __construct(string $file_name)
    {
        // Constructor initialise output file name
        $this->file_name = $file_name;
    }

    private function is_weekend(string $date):bool
    {
        // check date is weekend
     
        $day = date('D', strtotime($date));
        if (in_array($day, self::WEEK_END_DAY)) {
            return true;
        }
        return false;
    }

    private function create_csv(array $data)
    {
        // Create output file
     
        $file = fopen("$this->file_name", 'w');
        fputcsv($file, array('Month Name', 'Salary Date', 'Bonus Date'));
        foreach ($data as $line) {
            fputcsv($file, $line);
        }
        fclose($file);
    }

    public function generate_csv_report()
    {
        // Generate csv report month wise
     
        $month = date('m');
        $year = date('Y');
        $report_arr = [];

        for ($month ; $month <= 12 ; $month++) {
            $date = new DateTime("$year-$month-01");
            $date->modify('last day of this month');
            $salary_date = $date->format('Y-m-d');

            if (self::is_weekend($salary_date)) {
                $date->modify('last friday of this month');
                $salary_date = $date->format('Y-m-d');
            }

            $date = new DateTime("$year-$month-15");
            $bonus_date = $date->format('Y-m-d');

            if (self::is_weekend($bonus_date)) {
                $date->modify('next wednesday');
                $bonus_date = $date->format('Y-m-d');
            }

            $month_name = $date->format('F');
            $salary_bonus_date_of_month = array( "$month_name", "$salary_date", "$bonus_date" );
            array_push($report_arr, $salary_bonus_date_of_month);
        } // for loop for month
        self::create_csv($report_arr);
    } // function generate_csv_report
}//class
$sale_obj = new Sale($argv[1]);
$sale_obj->generate_csv_report();
