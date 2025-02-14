<?php
require_once "../src/FileVersionManager.php";   // Include the main library

// Initialize FileVersionManager with the default storage path
$manager = new FileVersionManager();

// Example file path (Original file) (You should replace this with an actual file)
$mainPath = "test_main.txt";

// Example file path (To review)
$filePath = "test_file.txt";

// Create a dummy file for demonstration (Only if it doesn't exist)
if (!file_exists($mainPath)) {
    file_put_contents($mainPath, "This is a test file for version control.");
}

// Create another test file for review
if (!file_exists($filePath)) {
    file_put_contents($filePath, "This is a test file for version control.");
}

// Unique version key for the file
$versionKey = "test_version_1";

try {
    // Step 1: Save a new version of the file
    echo "🔹 Saving file version...<br>";
    $manager->saveVersion($mainPath, $versionKey, "Initial version of the file.");
    echo "✅ File version saved successfully!<br><br>";

    // Step 2: Verify the stored version
    echo "🔹 Verifying file version...<br>";
    $isValid = $manager->verifyVersion($filePath, $versionKey);
    echo $isValid ? "✅ File version is valid!<br><br>" : "❌ File version mismatch!<br><br>";

    // Step 3: Retrieve version information
    echo "🔹 Fetching version details...<br>";
    $versionInfo = $manager->fetchVersionInfo($versionKey);
    echo "📌 Version Details:<br>";
    echo "- Timestamp: " . $versionInfo['timestamp'] . "<br>";
    echo "- Comment: " . $versionInfo['comment'] . "<br>";
    echo "- File Size: " . $versionInfo['size'] . " bytes<br><br>";

    // Step 4: Count total stored versions
    echo "🔹 Counting stored versions...<br>";
    $totalVersions = $manager->countVersions();
    echo "📊 Total Versions Stored: $totalVersions<br><br>";

    // Step 5: Delete a stored version
    echo "🔹 Deleting version...<br>";
    $manager->deleteVersion($versionKey);
    echo "✅ Version deleted successfully!<br><br><br>";

    // Delete sample file for testing
    // unlink($mainPath);
    // unlink($filePath);

    // Thank you for using this library
    echo "• Created by Hojjat Jahanpour under the MIT License.<br>";
    echo "❤️ Thank you for using this library!";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}
?>