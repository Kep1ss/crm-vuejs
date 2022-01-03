<?php

use Illuminate\Database\Migrations\Migration;

class SpAutoCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        $procedure = "DROP PROCEDURE IF EXISTS `sp_auto`;
          CREATE PROCEDURE `sp_auto`(
            IN tb VARCHAR (100),
            IN isField VARCHAR (20),
            IN kd VARCHAR (4)
          )
          BEGIN
            DECLARE digit INT ;
            DECLARE isNomer INT ;
            DECLARE isKode VARCHAR (100) ;
            DECLARE kode VARCHAR (50) ;
            DECLARE str TEXT ;
            SET @str = CONCAT(
              ' SELECT IFNULL(MAX(RIGHT(',
              isField,
              ',4)),0) into @jml FROM ',
              tb,' WHERE ',isField,' LIKE \"%',DATE_FORMAT(CURDATE(),'%y%m%d'),'%\"'
            ) ;
            PREPARE stmt FROM @str ;
            EXECUTE stmt ;
            DEALLOCATE PREPARE stmt ;
            SET digit = @jml ;
            IF digit = 0 
            THEN SET isNomer = 1 ;
            ELSE SET isNomer = digit + 1 ;
            END IF ;
            SET isKode = CONCAT(kd,DATE_FORMAT(CURDATE(),'%y%m%d'),'0000') ;
            SET kode = SUBSTRING(isKode,1,LENGTH(iskode)-LENGTH(CONVERT(isNomer,CHAR)));
            SET kode = RPAD(kode,LENGTH(isKode),isNomer);
           
            SELECT 
              kode ;
          END;";
  
        \DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}