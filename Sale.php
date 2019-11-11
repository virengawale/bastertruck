<?php
/**
 *1. Take input file name
 *2. Sales Department Salary and bonus Date report
 *3. Salary processed last day of the month if last day is weekend then proccessed salery on last working day of the month
 *4. Bonus is calculated on 15th of the month for the previus month if 15th is weekend then processed bonus on wenesday after 15th
 *5. Create report for remaining year in .csv file( Format :  format month, salary date, bonus dates)
*/

class Sale
{
    /**
     * @param string $file_name
     * @param array WEEK_END_DAY
     */
    private $file_name = null;
    const WEEK_END_DAY = array( 'Sat', 'Sun' );

    /**
    * Initialize output file name
    *
    * @param string $file_name
    */
    public function __construct(string $file_name)
    {
        $this->file_name = $file_name;
    }

    /**
    * Check given date is weekend and return true if weekend else return false
    *
    * @param string $date
    * @return bool
    */
    private function is_weekend(string $date):bool
    {
        $day = date('D', strtotime($date));
        if (in_array($day, self::WEEK_END_DAY)) {
            return true;
        }
        return false;
    }

    /**
    * Create csv format output file
    *
    * @param array $data 
    * @return create file in file system
    */
    private function create_csv(array $data)
    {
        $file = fopen("$this->file_name", 'w');
        fputcsv($file, array('Month Name', 'Salary Date', 'Bonus Date'));
        foreach ($data as $line) {
            fputcsv($file, $line);
        }
        fclose($file);
    }

    /**
    * business logic which takes care of identify
    * 1. Remaining month of current year
    * 2. Salary and bonus dates
    * 3. Call function create_csv() for generate output file
    *
    * @return void
    */
    public function generate_csv_report()
    {
        /**
         * @param string $month
         * @param string $year
         * @param array $report_arr
         */
        $month = date('m');
        $year = date('Y');
        $report_arr = [];

        for ($month ; $month <= 12 ; $month++) {
            // Initialize salary date
            $date = new DateTime("$year-$month-01");
            $date->modify('last day of this month');
            $salary_date = $date->format('Y-m-d');

            if (self::is_weekend($salary_date)) {
                $date->modify('last friday of this month');
                $salary_date = $date->format('Y-m-d');
            }

            // Initialize bonus date 
            $date = new DateTime("$year-$month-15");
            $bonus_date = $date->format('Y-m-d');

            if (self::is_weekend($bonus_date)) {
                $date->modify('next wednesday');
                $bonus_date = $date->format('Y-m-d');
            }

            $month_name = $date->format('F');
            $salary_bonus_date_of_month = array( "$month_name", "$salary_date", "$bonus_date" );
            //Create array for output
            array_push($report_arr, $salary_bonus_date_of_month);
        } 
        // Generate output file
        self::create_csv($report_arr);
    } 
} 

$file_name = $argv[1];
/**
 * Create oobject of sale class
 *
 * @param string $file_name
 * @return object
 */

$sale_obj = new Sale($file_name);
$sale_obj->generate_csv_report();
