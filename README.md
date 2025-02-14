# 📁 File Version Manager

## 📌 Description
**File Version Manager** is a PHP library for managing file versions. It allows you to store different versions of a file, verify changes, retrieve version details, and enforce security measures for stored files. The library efficiently handles large files up to **9.98GB**.

---

## 🚀 Features
- ✅ Store file versions with a unique key.
- 🔍 Verify if a given file matches a stored version.
- 📝 Retrieve metadata (timestamp, comment, and size) for a specific version.
- 📊 Count the total stored versions.
- 🚫 Prevent direct access to stored versions using `.htaccess`.
- ⚡ Optimized for large files using **streamed hashing** (SHA-256).
- 📏 Limits maximum file size to **9.98GB**.

- ⭐️ Suitable for management systems such as bot development projects.
---

## 📥 Installation

#####Clone the repository using:
```
git clone https://github.com/hojjatjh/FileVersionManager.git
```

---

## 🔧 Usage


#### 1️⃣ Initialize the Manager:
```php
require "FileVersionManager/src/FileVersionManager.php";
$manager = new FileVersionManager();
```

#### 2️⃣ Store a File Version:
The saveVersion method is used to store a new version of a file. It generates a unique hash for the file and saves metadata such as timestamp, comment, and file size. This ensures version tracking and integrity verification.

**Usage:**
```php
$manager->saveVersion("sample.php", "version1", "Initial version of the file.");
```

**Parameters:**

| Parameter   | Type  | Description  |
| ------------ | ------------ | ------------ |
| filePath |  string  | 	Path to the file to be stored (main)  |
| key  |string   |  Unique identifier for the file version |
| comment  |string (optional)	   |  A short description of this version |

⚠️** Important Notes:**
Each version key ($key) must be unique. You cannot store multiple files under the same key.
If the specified key already exists, an exception will be thrown to prevent overwriting an existing version.

#### 3️⃣ Verify a File Against a Stored Version:
The verifyVersion method checks whether a given file matches a previously stored version by comparing its hash value. This ensures data integrity and helps detect any changes in the file.

**Usage:**
```php
$isSame = $manager->verifyVersion("sample_updated.php", "version1");
echo $isSame ? "✅ Files are identical." : "❌ Files have changed.";
```

**Parameters:**

| Parameter   | Type  | Description  |
| ------------ | ------------ | ------------ |
| filePath |  string  | 	Path to the file that needs verification  |
| key  |string   |  Unique identifier of the stored version to compare against |


#### 4️⃣ Fetch Version Information:
The fetchVersionInfo method retrieves metadata (timestamp, comment, and file size) for a specific stored version.

**Usage:**
```php
$info = $manager->fetchVersionInfo("version1");
print_r($info);
```

**Parameters:**

| Parameter   | Type  | Description  |
| ------------ | ------------ | ------------ |
| key  |string   |  Unique identifier of the stored version to fetch information |


#### 5️⃣ Get Total Stored Versions:
The countVersions method retrieves the total number of stored file versions in the system.

**Usage:**
```php
echo "Total versions stored: " . $manager->countVersions();
```

**Parameters:**
This method does not require any parameters.


#### 6️⃣ Delete a Version:
The deleteVersion method removes a specific stored version from the version management system.

**Usage:**
```php
$manager->deleteVersion("version1");
```

**Parameters:**

| Parameter   | Type  | Description  |
| ------------ | ------------ | ------------ |
| key  |string   |  The unique version key to delete |

---

## 🔒 Security Measures
- The system denies direct access to stored versions using .htaccess.
- Uses SHA-256 hashing with a 1MB buffer to efficiently process large files.

---

## 📜 License
This project is licensed under the **MIT** License.