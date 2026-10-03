# Upgrade Guide

Upgrading an existing Directory Lister installation is quick and painless.

1. Download the latest version of Directory Lister from [https://www.directorylister.com](https://www.directorylister.com)
2. Extract the zip/tar archive
3. Copy the extracted files/folders to your existing installation location
   1. If prompted, overwriting all files
4. Clear the application cache by deleting all files in the `app/cache` directory

    ```bash
    rm --recursive --force app/cache/*
    ```
