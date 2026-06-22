Set WshShell = CreateObject("WScript.Shell")
WshShell.Run "cmd /c cd /d c:\xampp82\htdocs\eadmisi-lite && php artisan serve --host=0.0.0.0 --port=8000", 0, False