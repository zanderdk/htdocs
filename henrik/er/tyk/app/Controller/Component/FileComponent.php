<?php
App::uses('Component', 'Controller');
class FileComponent extends Component
{
	// Uploads a file to the server
	public function uploadFile($file, $fileName, $fileExtension = 'file', $isImage = false, $path = null)
	{	
		// If a file was given
		if(FileComponent::fileGiven($file))
		{	
			// Check if it is an image
			if($isImage){
				$type = explode('/', $file['type']);
			    if($type[0] != 'image')
			    {
			    	return false;
			    }
			}

			// Check if the pdf is actually a pdf
			if($fileExtension == 'pdf')
			{
				$type = explode('/', $file['type']);
			    if($type[1] != 'pdf')
			    {
			    	return false;
			    }
			}
			// Create a correct path
			$filePath = FileComponent::createFilePath($path, $fileName, $fileExtension, $isImage);

			// If there where no error uploading the file to the temporary server
			if($file['error'] == UPLOAD_ERR_OK)
			{	
				// Upload it to the ftp
				if(move_uploaded_file($file['tmp_name'],$filePath))
				{
					return true;
				}
			}
		}

		return false;
	}

	// Removes a file from the server
	public function deleteFile($fileName, $fileExtension = 'file', $isImage = false, $path = null)
	{
		// Create a correct path
		$filePath = FileComponent::createFilePath($path, $fileName, $fileExtension, $isImage);

		// Instanciate the file on the ftp
		$file = new File($filePath);

		// Delete the ftp and return if it was a success
		return $file->delete();
	}

	// Copy a file on the server (with the possibility of renaming it and the file extension)
	public function copyFile($sourceFileName, $sourceFileExtension = 'file', $sourceIsImage = false, $sourcePath = null,
							 $destFileName, $destFileExtension = 'file', $destIsImage = false, $destPath = null)
	{
		// If the newpath was not given
		if($destPath == null)
		{
			$destPath = $sourcePath;
		}

		// Create a correct path (where the file is at first)
		$sourceFilePath = FileComponent::createFilePath($sourcePath, $sourceFileName, $sourceFileExtension, $sourceIsImage);

		// Create a correct path (where the file should be placed)
		$destFilePath = FileComponent::createFilePath($destPath, $destFileName, $destFileExtension, $destIsImage);

		// Instanciate the file on the ftp
		$sourceFile = new File($sourceFilePath);

		// Copy the file to the new path on the ftp
		$sourceFile->copy($destFilePath);
	}

	// If the file i given (used in views)
	public function fileGiven($file)
	{
		// Check if a file was given
		if(!empty($file['tmp_name']))
		{
			return true;
		}
		
		return false;
	}

	// Does the file exist on the server
	public function fileExists($fileName, $fileExtension = 'file', $isImage = false, $path = null)
	{
		// Create a correct path
		$filePath = FileComponent::createFilePath($path, $fileName, $fileExtension, $isImage);

		// Instanciate the file on the ftp
		$file = new File($filePath);

		return $file->exists();
	}

	// Uploads an image and 
	public function uploadAndResizeImage($file, $fileName, $fileExtension = 'png', $path = null, $width = PHP_INT_MAX, $height =  PHP_INT_MAX)
	{
		if(FileComponent::uploadFile($file, $fileName, $fileExtension, true, $path))
		{
			return FileComponent::resizeAndCopyImage($fileName, $fileExtension, $path, $width, $height);
		}

		return false;
	}

	// Copies a file and resizes it according to its limit (keeps scale)
	public function resizeAndCopyImage($sourceFileName, $sourceFileExtension, $sourcePath = null, $destWidth, $destHeight, $destFileName = null, $destFileExtension = null, $destPath = null)
	{
		// If the destination file name is not set
		if($destFileName == null)
		{
			$destFileName = $sourceFileName;
		}

		// If the fileextension is not set
		if($destFileExtension == null)
		{
			$destFileExtension = $sourceFileExtension;
		}

		// If a custom path is not given to the destination
		if($destPath == null)
		{
			$destPath = $sourcePath;
		}
		
		// Generate the paths for the images
		$sourcePath = FileComponent::createFilePath($sourcePath, $sourceFileName, $sourceFileExtension, true);
		$destPath = FileComponent::createFilePath($destPath, $destFileName, $destFileExtension, true);

	    $sourceFile = new File($sourcePath);
	    $sourceFileInformation = $sourceFile->info();

	    // Check if everything has one of the allowed file extension
		$allowedExtensions = array("gif", "jpeg", "png");
		if(!in_array($sourceFileExtension, $allowedExtensions) ||
		   !in_array($destFileExtension, $allowedExtensions) ||
		   !in_array($sourceFileInformation['extension'], $allowedExtensions))
		{
			return false;
		}

		// Fetch data about the image
	    list($sourceWidth, $sourceHeight, $sourceImageType) = getimagesize($sourcePath);

	    // Create an image in the cache according to the type
	    switch ($sourceImageType) 
	    {
	        case IMAGETYPE_GIF:
	            $destGDImage = imagecreatefromgif($sourcePath);
	            break;
	        case IMAGETYPE_JPEG:
	            $destGDImage = imagecreatefromjpeg($sourcePath);
	            break;
	        case IMAGETYPE_PNG:
	            $destGDImage = imagecreatefrompng($sourcePath);
	            break;
	    }
	    if ($destGDImage === false) 
	    {
	        return false;
	    }
	    // Calcluates the aspects of the old and the new image
	    $sourceAspectRatio = $sourceWidth / $sourceHeight;
	    $destAspecRatio = $destWidth / $destHeight;

	    // If the source image is smaller than the requested one
	    if ($sourceWidth <= $destWidth && $sourceHeight <= $destHeight)
	    {
	        $newWidth = $sourceWidth;
	        $newHeight = $sourceHeight;
	    } 
	    // If the request image is wider than the source
	    elseif ($destAspecRatio > $sourceAspectRatio) 
	    {
	        $newWidth = (int) ($destHeight * $sourceAspectRatio);
	        $newHeight = $destHeight;
	    } 
	    // If the request image is taller than the source
	    else 
	    {
	        $newWidth = $destWidth;
	        $newHeight = (int) ($destWidth / $sourceAspectRatio);
	    }

	    // Generate an image width the calculated width and height
	    $newGDImage = imagecreatetruecolor($newWidth, $newHeight);

	    // Resize (resample) the image
	    imagecopyresampled($newGDImage, $destGDImage, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);
	    
	    // Check what output that is wanted
	    switch ($sourceImageType) 
	    {
	        case IMAGETYPE_GIF:
	            imagegif($newGDImage, $destPath);
	            break;
	        case IMAGETYPE_JPEG:
	            imagejpeg($newGDImage, $destPath, 75);
	            break;
	        case IMAGETYPE_PNG:
	            imagepng($newGDImage, $destPath, 9);
	            break;
	    }

	    // Destroy the images in the cache
	    imagedestroy($destGDImage);
	    imagedestroy($newGDImage);

	    return true;
	}

	public function getFile($file, $fileName, $fileExtension = 'file', $isImage = false, $path = null)
	{
		if (FileComponent::fileExists($fileName, $fileExtension, $isImage, $path))
		{
			$filePath = FileComponent::createFilePath($path, $fileName, $fileExtension, $isImage);
			return new File($filePath);
		}
		else
		{
			return false;
		}
	}

	// String manupulation in order to create 
	public function createFilePath($path, $fileName, $fileExtension = 'file', $isImage = false)
	{

		// Replaces all slashes with the standard DirectorySeperator
		$path = str_replace ('/' , DS , $path);

		//Puts the file or image into its according folder
		if($isImage)
		{
			// Sets it to /img/path
			$path =  'img'.DS.$path;
		}
		else
		{
			// Sets it to /file/path
			$path = 'files'.DS.$path;
		}

		// Sets the path to /webroot/..(file or img)../path
		$path = APP.'webroot'.DS.$path.DS;

		// Remove any double accurences of the DirectorySeperator
		$path = str_replace(array(
        "\\\\",
        "\\/", 
        "//", 
        "\\/", 
        "/\\"), DS, $path);

		// Removes '.' from the fileExtension
		$fileExtension = trim($fileExtension,'.');

		 return $path . $fileName . '.' . $fileExtension;
	}

}

?>