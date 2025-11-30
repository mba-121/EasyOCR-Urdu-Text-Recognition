 <?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Easy OCR Converter</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/indexstyle.css" />
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white px-3">
  <a class="navbar-brand" href="index.php">Easy OCR Converter</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
      <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
      <li class="nav-item"><a class="nav-link" href="faq.php">FAQ</a></li>
    </ul>
    <div class="text-end">
      <?php if (isset($_SESSION['username'])): ?>
        <div class="dropdown">
          <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <?php echo htmlspecialchars($_SESSION['username']); ?>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
              <li><a class="dropdown-item" href="admin_dashboard.php">Admin Dashboard</a></li>
            <?php endif; ?>
            <li><a class="dropdown-item" href="history.php">My Upload History</a></li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
          </ul>
        </div>
      <?php else: ?>
        <a href="login.php" class="btn btn-primary me-2">Log In</a>
        <a href="signup.php" class="btn btn-warning me-2">Sign Up</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<div class="banner">
  <h1>Free Easy OCR Converter in Urdu.</h1>
</div>

<section class="description">
  <p>Convert any image to Urdu text online using this free OCR tool. Upload an image and extract text instantly!</p>
</section>

<section class="ocr-tool">
  <h2>OCR Tool</h2>
  <p>Upload an image and click Convert to extract Urdu text.</p>

  <div class="upload-buttons">
    <button class="upload-btn" onclick="document.getElementById('fileInput').click();">Upload Image</button>
    <input type="file" id="fileInput" accept="image/*" style="display:none;" />
  </div>

  <div class="file-drop" id="dropZone">
    <p>Drag 'n' drop file here, or click to select file</p>
  </div>

  <div class="preview" id="previewSection">
    <p class="upload-message" id="uploadMessage" style="display:none;">File Uploaded</p>
    <img id="previewImage" style="display:none;" alt="Preview Image" />
  </div>

  <div class="language-select">
    <p>Selected Language: Urdu</p>
    <button class="convert-btn">Convert Now</button>
  </div>
</section>

<section class="ocr-output container my-4">
  <h3>Extracted Text</h3>
  <textarea id="ocrOutput" class="form-control" rows="8" placeholder="The extracted text will appear here..." readonly></textarea>
  <button class="btn btn-outline-primary mt-2" onclick="copyToClipboard()">Copy Text</button>
</section>

<section id="features" class="features-section container my-5">
  <h2 class="features-title">Key Features</h2>
  <ul class="features-list row">
    <li class="col-md-6 mb-3 d-flex align-items-start"><span class="feature-icon text-success me-2">&#10003;</span> <div><strong>Accuracy:</strong> Detects Urdu text precisely.</div></li>
    <li class="col-md-6 mb-3 d-flex align-items-start"><span class="feature-icon text-success me-2">&#10003;</span> <div><strong>Speed:</strong> Fast and efficient conversion.</div></li>
    <li class="col-md-6 mb-3 d-flex align-items-start"><span class="feature-icon text-success me-2">&#10003;</span> <div><strong>User-Friendly:</strong> Easy drag-and-drop interface.</div></li>
    <li class="col-md-6 mb-3 d-flex align-items-start"><span class="feature-icon text-success me-2">&#10003;</span> <div><strong>Free:</strong> Unlimited use, no cost.</div></li>
  </ul>
</section>

<script>
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('fileInput');
const previewImage = document.getElementById('previewImage');
const uploadMessage = document.getElementById('uploadMessage');
const convertBtn = document.querySelector('.convert-btn');
const outputText = document.getElementById('ocrOutput');

dropZone.addEventListener('click', () => fileInput.click());
fileInput.addEventListener('change', handleFile);

function handleFile() {
  const file = fileInput.files[0];
  if (file) {
    previewImage.src = URL.createObjectURL(file);
    previewImage.style.display = 'block';
    uploadMessage.style.display = 'block';
  }
}

convertBtn.addEventListener('click', () => {
  const file = fileInput.files[0];
  if (!file) {
    alert("Please upload an image first.");
    return;
  }

  const formData = new FormData();
  formData.append("image", file);

  fetch("https://ahmadali1.pythonanywhere.com/extract-text", {
    method: "POST",
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      const text = data.text || "No text found or an error occurred.";
      outputText.value = text;

      // Save to PHP backend
      const saveFormData = new FormData();
      saveFormData.append("filename", file.name);
      saveFormData.append("text", text);

      return fetch("save_ocr.php", {
        method: "POST",
        body: saveFormData
      });
    })
    .then(saveRes => saveRes.json())
    .then(saveResult => {
      if (saveResult.success) {
        console.log("Saved to history.");
      } else {
        console.error("Save failed:", saveResult.error);
      }
    })
    .catch(err => {
      outputText.value = "Error: " + err.message;
    });
});

function copyToClipboard() {
  const output = document.getElementById("ocrOutput");
  output.select();
  document.execCommand("copy");
}
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<div class="container">
  <footer class="py-3 my-4">
    <ul class="nav justify-content-center border-bottom pb-3 mb-3">
      <li class="nav-item"><a href="index.php" class="nav-link px-2 text-body-secondary">Home</a></li>
      <li class="nav-item"><a href="faq.php" class="nav-link px-2 text-body-secondary">FAQs</a></li>
      <li class="nav-item"><a href="about.php" class="nav-link px-2 text-body-secondary">About</a></li>
    </ul>
    <p class="text-center text-body-secondary">© 2025 Company, Inc</p>
  </footer>
</div>

</body>
</html>