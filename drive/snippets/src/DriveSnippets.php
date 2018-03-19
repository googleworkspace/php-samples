<?php
class DriveSnippets
{

    public function __construct($service)
    {
        $this->service = $service;
    }

    public function uploadBasic()
    {
        $driveService = $this->service;
        // [START uploadBasic]
        $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => 'photo.jpg'));
        $content = file_get_contents('files/photo.jpg');
        $file = $driveService->files->create($fileMetadata, array(
            'data' => $content,
            'mimeType' => 'image/jpeg',
            'uploadType' => 'multipart',
            'fields' => 'id'));
        printf("File ID: %s\n", $file->id);
        // [END uploadBasic]
        return $file->id;
    }

    public function uploadToFolder($realFolderId)
    {
        $driveService = $this->service;
        // [START uploadToFolder]
        $folderId = '0BwwA4oUTeiV1TGRPeTVjaWRDY1E';
        // [START_EXCLUDE silent]
        $folderId = $realFolderId;
        // [END_EXCLUDE]
        $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => 'photo.jpg',
            'parents' => array($folderId)
        ));
        $content = file_get_contents('files/photo.jpg');
        $file = $driveService->files->create($fileMetadata, array(
            'data' => $content,
            'mimeType' => 'image/jpeg',
            'uploadType' => 'multipart',
            'fields' => 'id'));
        printf("File ID: %s\n", $file->id);
        // [END uploadToFolder]
        return $file->id;
    }

    public function uploadWithConversion()
    {
        $driveService = $this->service;
        // [START uploadWithConversion]
        $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => 'My Report',
            'mimeType' => 'application/vnd.google-apps.spreadsheet'));
        $content = file_get_contents('files/report.csv');
        $file = $driveService->files->create($fileMetadata, array(
            'data' => $content,
            'mimeType' => 'text/csv',
            'uploadType' => 'multipart',
            'fields' => 'id'));
        printf("File ID: %s\n", $file->id);
        // [END uploadWithConversion]
        return $file->id;
    }

    // TODO - Currently broken due to PHP client confiscating 'mimeType' param
    public function exportPdf($realFileId)
    {
        $driveService = $this->service;
        // [START exportPdf]
        $fileId = '1ZdR3L3qP4Bkq8noWLJHSr_iBau0DNT4Kli4SxNc2YEo';
        // [START_EXCLUDE silent]
        $fileId = $realFileId;
        // [END_EXCLUDE]
        $response = $driveService->files->export($fileId, 'application/pdf', array(
            'alt' => 'media'));
        $content = $response->getBody()->getContents();
        // [END exportPdf]
        return $content;
    }

    public function downloadFile($realFileId)
    {
        $driveService = $this->service;
        // [START downloadFile]
        $fileId = '0BwwA4oUTeiV1UVNwOHItT0xfa2M';
        // [START_EXCLUDE silent]
        $fileId = $realFileId;
        // [END_EXCLUDE]
        $response = $driveService->files->get($fileId, array(
            'alt' => 'media'));
        $content = $response->getBody()->getContents();
        // [END downloadFile]
        return $content;
    }

    public function createShortcut()
    {
        $driveService = $this->service;
        // [START createShortcut]
        $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => 'Project plan',
            'mimeType' => 'application/vnd.google-apps.drive-sdk'));
        $file = $driveService->files->create($fileMetadata, array(
            'fields' => 'id'));
        printf("File ID: %s\n", $file->id);
        // [END createShortcut]
        return $file->id;
    }

    public function touchFile($realFileId, $realModifiedTime)
    {
        $driveService = $this->service;
        // [START touchFile]
        $fileId = '1sTWaJ_j7PkjzaBWtNc3IzovK5hQf21FbOw9yLeeLPNQ';
        $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'modifiedTime' => date('Y-m-d\TH:i:s.uP')));
        // [START_EXCLUDE silent]
        $fileId = $realFileId;
        $fileMetadata->modifiedTime = $realModifiedTime;
        // [END_EXCLUDE]
        $file = $driveService->files->update($fileId, $fileMetadata, array(
            'fields' => 'id, modifiedTime'));
        printf("Modified time: %s\n", $file->modifiedTime);
        // [END touchFile]
        return $file->modifiedTime;
    }

    public function createFolder()
    {
        $driveService = $this->service;
        // [START createFolder]
        $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => 'Invoices',
            'mimeType' => 'application/vnd.google-apps.folder'));
        $file = $driveService->files->create($fileMetadata, array(
            'fields' => 'id'));
        printf("Folder ID: %s\n", $file->id);
        // [END createFolder]
        return $file->id;
    }

    public function moveFileToFolder($realFileId, $realFolderId)
    {
        $driveService = $this->service;
        // [START moveFileToFolder]
        $fileId = '1sTWaJ_j7PkjzaBWtNc3IzovK5hQf21FbOw9yLeeLPNQ';
        $folderId = '0BwwA4oUTeiV1TGRPeTVjaWRDY1E';
        $emptyFileMetadata = new Google_Service_Drive_DriveFile();
        // [START_EXCLUDE silent]
        $fileId = $realFileId;
        $folderId = $realFolderId;
        // [END_EXCLUDE]
        // Retrieve the existing parents to remove
        $file = $driveService->files->get($fileId, array('fields' => 'parents'));
        $previousParents = join(',', $file->parents);
        // Move the file to the new folder
        $file = $driveService->files->update($fileId, $emptyFileMetadata, array(
            'addParents' => $folderId,
            'removeParents' => $previousParents,
            'fields' => 'id, parents'));
        // [END moveFileToFolder]
        return $file->parents;
    }

    function searchFiles()
    {
        $driveService = $this->service;
        $files = array();
        // [START searchFiles]
        $pageToken = null;
        do {
            $response = $driveService->files->listFiles(array(
                'q' => "mimeType='image/jpeg'",
                'spaces' => 'drive',
                'pageToken' => $pageToken,
                'fields' => 'nextPageToken, files(id, name)',
            ));
            foreach ($response->files as $file) {
                printf("Found file: %s (%s)\n", $file->name, $file->id);
            }
            // [START_EXCLUDE silent]
            array_push($files, $response->files);
            // [END_EXCLUDE]

            $pageToken = $response->pageToken;
        } while ($pageToken != null);
        // [END searchFiles]
        return $files;
    }

    function shareFile($realFileId, $realUser, $realDomain)
    {
        $driveService = $this->service;
        $ids = array();
        // [START shareFile]
        $fileId = '1sTWaJ_j7PkjzaBWtNc3IzovK5hQf21FbOw9yLeeLPNQ';
        // [START_EXCLUDE silent]
        $fileId = $realFileId;
        // [END_EXCLUDE]
        $driveService->getClient()->setUseBatch(true);
        try {
            $batch = $driveService->createBatch();

            $userPermission = new Google_Service_Drive_Permission(array(
                'type' => 'user',
                'role' => 'writer',
                'emailAddress' => 'user@example.com'
            ));
            // [START_EXCLUDE silent]
            $userPermission['emailAddress'] = $realUser;
            // [END_EXCLUDE]
            $request = $driveService->permissions->create(
                $fileId, $userPermission, array('fields' => 'id'));
            $batch->add($request, 'user');
            $domainPermission = new Google_Service_Drive_Permission(array(
                'type' => 'domain',
                'role' => 'reader',
                'domain' => 'example.com'
            ));
            // [START_EXCLUDE silent]
            $userPermission['domain'] = $realDomain;
            // [END_EXCLUDE]
            $request = $driveService->permissions->create(
                $fileId, $domainPermission, array('fields' => 'id'));
            $batch->add($request, 'domain');
            $results = $batch->execute();

            foreach ($results as $result) {
                if ($result instanceof Google_Service_Exception) {
                    // Handle error
                    printf($result);
                } else {
                    printf("Permission ID: %s\n", $result->id);
                    // [START_EXCLUDE silent]
                    array_push($ids, $result->id);
                    // [END_EXCLUDE]
                }
            }
        } finally {
            $driveService->getClient()->setUseBatch(false);
        }
        // [END shareFile]
        return $ids;
    }

    public function uploadAppData()
    {
        $driveService = $this->service;
        // [START uploadAppData]
        $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => 'config.json',
            'parents' => array('appDataFolder')
        ));
        $content = file_get_contents('files/config.json');
        $file = $driveService->files->create($fileMetadata, array(
            'data' => $content,
            'mimeType' => 'application/json',
            'uploadType' => 'multipart',
            'fields' => 'id'));
        printf("File ID: %s\n", $file->id);
        // [END uploadAppData]
        return $file->id;
    }

    public function listAppData()
    {
        $driveService = $this->service;
        // [START listAppData]
        $response = $driveService->files->listFiles(array(
            'spaces' => 'appDataFolder',
            'fields' => 'nextPageToken, files(id, name)',
            'pageSize' => 10
        ));
        foreach ($response->files as $file) {
            printf("Found file: %s (%s)", $file->name, $file->id);
        }
        // [END listAppData]
        return $response->files;
    }

    public function fetchAppDataFolder()
    {
        $driveService = $this->service;
        // [START fetchAppDataFolder]
        $file = $driveService->files->get('appDataFolder', array(
            'fields' => 'id'
        ));
        printf("Folder ID: %s\n", $file->id);
        // [END fetchAppDataFolder]
        return $file->id;
    }

    # TODO - PHP client currently chokes on fetching start page token
    public function fetchStartPageToken()
    {
        $driveService = $this->service;
        # [START fetchStartPageToken]
        $response = $driveService->changes->getStartPageToken();
        printf("Start token: %s\n", $response->startPageToken);
        # [END fetchStartPageToken]
        return $response->startPageToken;
    }

    public function fetchChanges($savedStartPageToken)
    {
        $driveService = $this->service;
        // [START fetchChanges]
        # Begin with our last saved start token for this user or the
        # current token from getStartPageToken()
        $pageToken = $savedStartPageToken;
        while ($pageToken != null) {
            $response = $driveService->changes->listChanges($pageToken, array(
                'spaces' => 'drive'
            ));
            foreach ($response->changes as $change) {
                // Process change
                printf("Change found for file: %s", $change->fileId);
            }
            if ($response->newStartPageToken != null) {
                // Last page, save this token for the next polling interval
                $savedStartPageToken = $response->newStartPageToken;
            }
            $pageToken = $response->nextPageToken;
        }
        // [END fetchChanges]
        return $savedStartPageToken;
    }
}
