' E-ADMISI LITE - Persistent Server Launcher (VBS)
' Runs php artisan serve in hidden window with auto-restart
' Double-click this file to start and it will stay alive

Dim shell, fso, logFile, projectPath
Set shell = CreateObject("WScript.Shell")
Set fso = CreateObject("Scripting.FileSystemObject")

projectPath = "c:\xampp82\htdocs\eadmisi-lite"
logFile = projectPath & "\storage\logs\server-watchdog.log"
logDir = fso.GetParentFolderName(logFile)

' Ensure log directory exists
If Not fso.FolderExists(logDir) Then
    fso.CreateFolder(logDir)
End If

Dim logStream
Set logStream = fso.OpenTextFile(logFile, 8, True)

' Log startup
logStream.WriteLine Now & " [VBS] Starting persistent Laravel server watcher..."
logStream.Close

' Run the batch file that has auto-restart loop (hidden window)
' 0 = hide window, False = don't wait
shell.Run "cmd /c """ & projectPath & "\start-server.bat""", 0, False

' Also try PowerShell persistent launcher (more reliable)
shell.Run "powershell.exe -ExecutionPolicy Bypass -WindowStyle Hidden -File """ & projectPath & "\start-server.ps1""", 0, False

Set shell = Nothing
Set fso = Nothing