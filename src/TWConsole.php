<?php 

namespace TW;

use InvalidArgumentException;

class TWConsole 
{

    const foreground_colors = [
        'black' => '0;30',
        'dark_gray' => '1;30',
        'blue' => '0;34',
        'light_blue' => '1;34',
        'green' => '0;32',
        'light_green' => '1;32',
        'cyan' => '0;36',
        'light_cyan' => '1;36',
        'red' => '0;31',
        'light_red' => '1;31',
        'purple' => '0;35',
        'light_purple' => '1;35',
        'brown' => '0;33',
        'yellow' => '1;33',
        'light_gray' => '0;37',
        'white' => '1;37',
    ];

    const background_colors = [
        'black' => '40',
        'red' => '41',
        'green' => '42',
        'yellow' => '43',
        'blue' => '44',
        'magenta' => '45',
        'cyan' => '46',
        'light_gray' => '47',
    ];

    /**
     * Use it to write a simple line
     * <code>
     * TWConsole::write('Hello world');
     * 
     * Output :
     * <code>
     * Hello world
     * </code>
     */
    public static function write(string $str, $foreground_color = null, $background_color = null) : void
    {
        echo sprintf('%s', self::colored($str, $foreground_color, $background_color));
    }

    /**
     * Use it to write a simple line with an escape character
     * <code>
     * TWConsole::writeLn('Hello world');
     * </code>
     * Output :
     * <code>
     * Hello world(\n)
     * </code>
     */
    public static function writeLn(string $str, $foreground_color = null, $background_color = null) : void
    {
        echo sprintf("%s\n", self::colored($str, $foreground_color, $background_color));
    }

    /**
     * Use it to write a line which is underlined
     * <code>
     * TWConsole::writeUnder('Hello world');
     * </code>
     * 
     * Output :
     * <code>
     * Hello world(\n)
     * -----------(\n)
     * </code>
     */
    public static function writeUnder(string $str, $foreground_color = null, $background_color = null) : void 
    {
        self::writeLn($str, $foreground_color, $background_color);
        self::writeSeparator('-', strlen($str));
    }

    /**
     * Use it to write a line that represents a separator
     * <code>
     * TWConsole::writeSeparator('-', 30);
     * </code>
     * 
     * Output :
     * <code>
     * (\n)------------------------------(\n)
     * </code>
     */
    public static function writeSeparator(string $separator = '-', int $size = 60) 
    {
        self::writeLn("\n".str_repeat($separator, $size));
    }

    /**
     * Use it to write a table
     * <code>
     * TWConsole::writeTable($header, $lines);
     * </code>
     * 
     * Output :
     * <code>
     * | Name    | Value |
     * +---------+-------+
     * | Param   | 60    |
     * | Foregro | false |
     * +---------+-------+
     * </code>
     * 
     * <code>
     * TWConsole::writeTable($header, $lines, true);
     * </code>
     * 
     * Output :
     * <code>
     * | Name    | Value |
     * +---------+-------+
     * | Param   | 60    |
     * +---------+-------+
     * | Foregro | false |
     * +---------+-------+
     * </code>
     * 
     * @param array $header The header of the table ['Name', 'Value]
     * @param array $lines The lines of the table [['Param', 60], ['Foregro', false]]
     * 
     */

    public static function writeTable(array $header, array $lines, bool $with_separator = false)
    {
        if(count($lines) == 0) {
            throw new InvalidArgumentException('The lines are empty');
        }

        $columnsLength = self::getMaxColumnsLengthForArray($header, $lines);
        
        self::printArrayLine($header, $columnsLength, true);
       
        foreach($lines as $line) {
            self::printArrayLine($line, $columnsLength, $with_separator);
        }

        if(!$with_separator) {
            self::printArraySeparator($columnsLength);
        }
    }

    private static function printArrayLine(array $array, array $columns_length, bool $with_separator = false) : void 
    {
        $cell_format = '| %s ';
        $end_line = '|';

        $line_print = '';
        $column = 0;
        foreach($array as $cell) {
            $line_print .= sprintf($cell_format, $cell.str_repeat(' ', $columns_length[$column] - strlen($cell)));
            $column++;
        }
        $line_print.=$end_line;

        self::write($line_print);

        if($with_separator) {
            self::printArraySeparator($columns_length);
        }
    }

    private static function printArraySeparator(array $columns_length) 
    {
        $separator_print = '+%s';
        $separator = '';

        for($column = 0; $column < count($columns_length); $column++) {
            $separator .= sprintf($separator_print, str_repeat('-', $columns_length[$column] + 2));
        }
        $separator .= sprintf($separator_print, '');

        self::write($separator);
    }

    private static function getMaxColumnsLengthForArray(array $header, array $content)
    {
        $maxHeader = self::getColumnsLength($header);
        $contentMax = self::getLinesColumnsLength($content);
        $numberOfColumns = count($maxHeader);
        $maxColumns = [];

        for($i = 0; $i < $numberOfColumns; $i++)
        {
            $columnValues = array_map(function($line) use($i) {
                return $line[$i];
            }, $contentMax);

            $maxLengthOfColumn = max($columnValues);
            
            if($maxHeader[$i] > $maxLengthOfColumn) 
            {
                $maxColumns[] = $maxHeader[$i];
            } else {
                $maxColumns[] = $maxLengthOfColumn;
            }
        }

        return $maxColumns;
        
    }

    private static function getColumnsLength(array $array) : array 
    {
        return array_values(array_combine($array, array_map('strlen', $array)));     
    }

    private static function getLinesColumnsLength(array $lines) : array 
    {
        $columns_length = [];
        foreach($lines as $line) 
        {
            $columns_length[] = self::getColumnsLength($line);
        }

        return $columns_length;
    }

    private static function colored($string, $foreground_color = null, $background_color = null) : string
    {
        $colored_string = "";

        if (isset(self::foreground_colors[$foreground_color])) {
            $colored_string .= "\033[" . self::foreground_colors[$foreground_color] . "m";
        }

        if (isset(self::background_colors[$background_color])) {
            $colored_string .= "\033[" . self::background_colors[$background_color] . "m";
        }

        $colored_string .=  $string . "\033[0m";

        return $colored_string;
    }
}