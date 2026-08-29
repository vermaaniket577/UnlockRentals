@echo off
(for /L %%i in (1,1,20) do @echo y) | call "C:\Users\aniket\AppData\Local\Android\Sdk\cmdline-tools\latest\bin\sdkmanager.bat" --licenses --sdk_root=C:\Users\aniket\AppData\Local\Android\Sdk
