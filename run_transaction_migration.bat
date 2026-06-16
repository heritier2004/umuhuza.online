 @echo off
 REM Batch script to run the migration that adds transaction_id column
 SET PHP_EXE=php
 REM If php is not in PATH, set full path e.g., C:\php\php.exe
 IF NOT "%~1"=="" SET PHP_EXE=%~1
 %PHP_EXE% add_transaction_id_migration.php
 pause
