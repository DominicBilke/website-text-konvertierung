<?php

function post_request($url, array $params) {
$postdata = http_build_query(
    $params
);

$opts = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peername' => false
        // Instead ideally use
        // 'cafile' => 'path Certificate Authority file on local filesystem'
    ),
    'http' => array(
        'method' => 'POST',
        'header' => 'Content-type: application/x-www-form-urlencoded'."\r\n",
        'content' => $postdata
    )
);

$context = stream_context_create($opts);

return file_get_contents($url, false, $context);
}

function deleteOlderFiles($path,$days) {
  if ($handle = opendir($path)) {
    while (false !== ($file = readdir($handle))) {
      $filelastmodified = filemtime($path . $file);
      if((time() - $filelastmodified) > $days*24*3600)
      {
        if(is_file($path . $file)) {
          unlink($path . $file);
        }
      }
    }
    closedir($handle);
  }
}

deleteOlderFiles('uploads/', 7);

$target_file_to = "./uploads/download_".uniqid().".txt";
if(isset($_POST['txt']) || isset($_POST['txtfile'])) {

$txt = $_POST['txt'];
if(isset($_FILES['txtfile'])) {
$target_file = "./uploads/download_".uniqid().".txt";
if (move_uploaded_file($_FILES['txtfile']["tmp_name"], $target_file))
	$txt = file_get_contents($target_file);
$txtfile=$target_file;
$txtTofile=$target_file_to;
}

$from = $_POST['from'];
$to = $_POST['to'];
// When you have your own client ID and secret, put them down here:
$CLIENT_ID = "";
$CLIENT_SECRET = "";
$txtTo ="";

$output = str_split($_POST['txt'], 2400);

foreach($output as $txttmp) {
// Specify your translation requirements here:
$postData = array(
  'fromLang' => $_POST['from'],
  'toLang' => $_POST['to'],
  'text' => $txttmp
);

$headers = array(
  'Content-Type: application/json',
  'X-WM-CLIENT-ID: '.$CLIENT_ID,
  'X-WM-CLIENT-SECRET: '.$CLIENT_SECRET
);


$url = 'http://api.whatsmate.net/v1/translation/translate';
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

$txtTo .= curl_exec($ch);

curl_close($ch);
}

file_put_contents($target_file_to, $txtTo);

$speech_arr = array('ar' => 'ar-eg',
'bg' => 'bg-bg',
'ca' => 'ca-es',
'zh-CN' => 'zh-cn',
'hr' => 'hr-hr',
'cs' => 'cs-cz',
'da' => 'da-dk',
'nl' => 'nl-be',
'en' => 'en-us',
'fi' => 'fi-fi',
'fr' => 'fr-fr',
'de' => 'de-de',
'el' => 'el-gr',
'hi' => 'hi-in',
'hu' => 'hu-hu',
'id' => 'id-id',
'it' => 'it-it',
'ja' => 'ja-jp',
'ko' => 'ko-kr',
'ms' => 'ms-my',
'nb' => 'nb-no', 
'pl' => 'pl-pl',
'pt' => 'pt-pt',
'ro' => 'ro-ro',
'ru' => 'ru-ru',
'sk' => 'sk-sk',
'sl' => 'sl-si',
'es' => 'es-es',
'sv' => 'sv-se',
'ta' => 'ta-in',
'th' => 'th-th',
'tr' => 'tr-tr',
'vi' => 'vi-vn');

}

$tesseract_arr = array('ar' => 'ar-eg',
'bg' => 'bul',
'ca' => 'cat',
'zh-CN' => 'chi_sim',
'hr' => 'hrv',
'cs' => 'ces',
'da' => 'dan',
'nl' => 'nld',
'en' => 'eng',
'fi' => 'fin',
'fr' => 'fra',
'de' => 'deu',
'el' => 'ell',
'hi' => 'hin',
'hu' => 'hun',
'id' => 'ind',
'it' => 'ita',
'ja' => 'jpn',
'ko' => 'kor',
'ms' => 'msa',
'nb' => 'nor', 
'po' => 'pol',
'pt' => 'por',
'ro' => 'ron',
'ru' => 'rus',
'sk' => 'slk',
'sl' => 'slv',
'es' => 'spa',
'sv' => 'swe',
'ta' => 'tam',
'th' => 'tha',
'tr' => 'tur',
'vi' => 'vie');

if(isset($_FILES['pdffile']["name"])) {
$target_dir = "uploads/";
$target_file = $target_dir . uniqid().'_'.basename($_FILES["pdffile"]["name"]);
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$target_file = $target_dir . uniqid().'.'.$imageFileType;
if (move_uploaded_file($_FILES["pdffile"]["tmp_name"], $target_file))
{
$Url      = "https://text-konvertierung.bilke-projects.com/convert_file.php?lang=".$tesseract_arr[$_POST['from']]."&fileToUpload=".$target_file; 
$txt = file_get_contents($Url);
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text-Konvertierung - Modern OCR & Speech Conversion</title>
    <meta name="description" content="Convert your texts with ease using our modern OCR, translation, and text-to-speech tools.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Modern Header -->
    <header class="modern-header">
        <div class="header-container">
            <a href="#home" class="logo">
                <i class="fas fa-language"></i> Text-Konvertierung
            </a>
            <nav>
                <ul class="nav-menu">
                    <li><a href="#home" class="nav-link">Home</a></li>
                    <li><a href="#ocr" class="nav-link">OCR</a></li>
                    <li><a href="#translation" class="nav-link">Translation</a></li>
                    <li><a href="#speech" class="nav-link">Speech</a></li>
                    <li><a href="#about" class="nav-link">About</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content fade-in-up">
            <h1>Convert Your Texts with Ease</h1>
            <p>Transform documents, translate languages, and convert text to speech with our powerful AI-powered tools. Professional, fast, and accurate.</p>
            <div class="hero-buttons">
                <a href="#ocr" class="btn btn-primary">
                    <i class="fas fa-camera"></i> Start OCR
                </a>
                <a href="#translation" class="btn btn-secondary">
                    <i class="fas fa-language"></i> Translate Now
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="features-container">
            <div class="section-header fade-in-up">
                <h2>Powerful Conversion Tools</h2>
                <p>Everything you need for text processing in one place</p>
            </div>
            <div class="features-grid">
                <div class="feature-card fade-in-up">
                    <div class="feature-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                    <h3>OCR to Text</h3>
                    <p>Extract text from images, PDFs, and scanned documents with high accuracy using advanced OCR technology.</p>
                </div>
                <div class="feature-card fade-in-up">
                    <div class="feature-icon">
                        <i class="fas fa-language"></i>
                    </div>
                    <h3>Multi-Language Translation</h3>
                    <p>Translate text between 30+ languages with professional-grade accuracy and natural language processing.</p>
                </div>
                <div class="feature-card fade-in-up">
                    <div class="feature-icon">
                        <i class="fas fa-volume-up"></i>
                    </div>
                    <h3>Text to Speech</h3>
                    <p>Convert any text into natural-sounding speech with multiple voice options and language support.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- OCR Section -->
    <section id="ocr" class="conversion-section">
        <div class="conversion-container">
            <div class="section-header fade-in-up">
                <h2>OCR Text Recognition</h2>
                <p>Upload your documents and images to extract editable text</p>
            </div>
            <form method="post" action="#translation" class="conversion-form fade-in-up" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="form-label">Select File</label>
                    <div class="file-upload">
                        <input type="file" name="pdffile" id="pdffile" accept=".jpg, .jpeg, .png, .gif, .pdf" required>
                        <label for="pdffile" class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt fa-2x"></i><br>
                            <strong>Click to upload</strong> or drag and drop<br>
                            <small>Supports: JPG, PNG, GIF, PDF</small>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="from" class="form-label">Document Language</label>
                    <select id="from" name="from" class="form-select" required>
                        <option value="de">German - Deutsch</option>
                        <option value="en">English</option>
                        <option value="fr">French - français</option>
                        <option value="es">Spanish - español</option>
                        <option value="it">Italian - italiano</option>
                        <option value="pt">Portuguese - português</option>
                        <option value="ru">Russian - русский</option>
                        <option value="zh-CN">Chinese - 中文</option>
                        <option value="ja">Japanese - 日本語</option>
                        <option value="ko">Korean - 한국어</option>
                        <option value="ar">Arabic - العربية</option>
                        <option value="hi">Hindi - हिन्दी</option>
                        <option value="bg">Bulgarian - български</option>
                        <option value="ca">Catalan - català</option>
                        <option value="hr">Croatian - hrvatski</option>
                        <option value="cs">Czech - čeština</option>
                        <option value="da">Danish - dansk</option>
                        <option value="nl">Dutch - Nederlands</option>
                        <option value="fi">Finnish - suomi</option>
                        <option value="el">Greek - Ελληνικά</option>
                        <option value="hu">Hungarian - magyar</option>
                        <option value="id">Indonesian - Indonesia</option>
                        <option value="ms">Malay - Bahasa Melayu</option>
                        <option value="nb">Norwegian - norsk</option>
                        <option value="pl">Polish - polski</option>
                        <option value="ro">Romanian - română</option>
                        <option value="sk">Slovak - slovenčina</option>
                        <option value="sl">Slovenian - slovenščina</option>
                        <option value="sv">Swedish - svenska</option>
                        <option value="ta">Tamil - தமிழ்</option>
                        <option value="th">Thai - ไทย</option>
                        <option value="tr">Turkish - Türkçe</option>
                        <option value="vi">Vietnamese - Tiếng Việt</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Recognize Text
                </button>
            </form>
        </div>
    </section>

    <!-- Translation Section -->
    <section id="translation" class="conversion-section">
        <div class="conversion-container">
            <div class="section-header fade-in-up">
                <h2>Text Translation</h2>
                <p>Translate your text between multiple languages with professional accuracy</p>
            </div>
            <form method="post" action="#speech" class="conversion-form fade-in-up" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="txt" class="form-label">Enter Text to Translate</label>
                    <textarea id="txt" name="txt" class="form-textarea" placeholder="Enter or paste your text here..." required><?php echo isset($txt) ? htmlspecialchars($txt) : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="from" class="form-label">From Language</label>
                    <select id="from" name="from" class="form-select" required>
                        <option value="de"<?php if(isset($from) && $from=="de") echo " selected"; ?>>German - Deutsch</option>
                        <option value="en"<?php if(isset($from) && $from=="en") echo " selected"; ?>>English</option>
                        <option value="fr"<?php if(isset($from) && $from=="fr") echo " selected"; ?>>French - français</option>
                        <option value="es"<?php if(isset($from) && $from=="es") echo " selected"; ?>>Spanish - español</option>
                        <option value="it"<?php if(isset($from) && $from=="it") echo " selected"; ?>>Italian - italiano</option>
                        <option value="pt"<?php if(isset($from) && $from=="pt") echo " selected"; ?>>Portuguese - português</option>
                        <option value="ru"<?php if(isset($from) && $from=="ru") echo " selected"; ?>>Russian - русский</option>
                        <option value="zh-CN"<?php if(isset($from) && $from=="zh-CN") echo " selected"; ?>>Chinese - 中文</option>
                        <option value="ja"<?php if(isset($from) && $from=="ja") echo " selected"; ?>>Japanese - 日本語</option>
                        <option value="ko"<?php if(isset($from) && $from=="ko") echo " selected"; ?>>Korean - 한국어</option>
                        <option value="ar"<?php if(isset($from) && $from=="ar") echo " selected"; ?>>Arabic - العربية</option>
                        <option value="hi"<?php if(isset($from) && $from=="hi") echo " selected"; ?>>Hindi - हिन्दी</option>
                        <option value="bg"<?php if(isset($from) && $from=="bg") echo " selected"; ?>>Bulgarian - български</option>
                        <option value="ca"<?php if(isset($from) && $from=="ca") echo " selected"; ?>>Catalan - català</option>
                        <option value="hr"<?php if(isset($from) && $from=="hr") echo " selected"; ?>>Croatian - hrvatski</option>
                        <option value="cs"<?php if(isset($from) && $from=="cs") echo " selected"; ?>>Czech - čeština</option>
                        <option value="da"<?php if(isset($from) && $from=="da") echo " selected"; ?>>Danish - dansk</option>
                        <option value="nl"<?php if(isset($from) && $from=="nl") echo " selected"; ?>>Dutch - Nederlands</option>
                        <option value="fi"<?php if(isset($from) && $from=="fi") echo " selected"; ?>>Finnish - suomi</option>
                        <option value="el"<?php if(isset($from) && $from=="el") echo " selected"; ?>>Greek - Ελληνικά</option>
                        <option value="hu"<?php if(isset($from) && $from=="hu") echo " selected"; ?>>Hungarian - magyar</option>
                        <option value="id"<?php if(isset($from) && $from=="id") echo " selected"; ?>>Indonesian - Indonesia</option>
                        <option value="ms"<?php if(isset($from) && $from=="ms") echo " selected"; ?>>Malay - Bahasa Melayu</option>
                        <option value="nb"<?php if(isset($from) && $from=="nb") echo " selected"; ?>>Norwegian - norsk</option>
                        <option value="pl"<?php if(isset($from) && $from=="pl") echo " selected"; ?>>Polish - polski</option>
                        <option value="ro"<?php if(isset($from) && $from=="ro") echo " selected"; ?>>Romanian - română</option>
                        <option value="sk"<?php if(isset($from) && $from=="sk") echo " selected"; ?>>Slovak - slovenčina</option>
                        <option value="sl"<?php if(isset($from) && $from=="sl") echo " selected"; ?>>Slovenian - slovenščina</option>
                        <option value="sv"<?php if(isset($from) && $from=="sv") echo " selected"; ?>>Swedish - svenska</option>
                        <option value="ta"<?php if(isset($from) && $from=="ta") echo " selected"; ?>>Tamil - தமிழ்</option>
                        <option value="th"<?php if(isset($from) && $from=="th") echo " selected"; ?>>Thai - ไทย</option>
                        <option value="tr"<?php if(isset($from) && $from=="tr") echo " selected"; ?>>Turkish - Türkçe</option>
                        <option value="vi"<?php if(isset($from) && $from=="vi") echo " selected"; ?>>Vietnamese - Tiếng Việt</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="to" class="form-label">To Language</label>
                    <select id="to" name="to" class="form-select" required>
                        <option value="en"<?php if(isset($to) && $to=="en") echo " selected"; ?>>English</option>
                        <option value="de"<?php if(isset($to) && $to=="de") echo " selected"; ?>>German - Deutsch</option>
                        <option value="fr"<?php if(isset($to) && $to=="fr") echo " selected"; ?>>French - français</option>
                        <option value="es"<?php if(isset($to) && $to=="es") echo " selected"; ?>>Spanish - español</option>
                        <option value="it"<?php if(isset($to) && $to=="it") echo " selected"; ?>>Italian - italiano</option>
                        <option value="pt"<?php if(isset($to) && $to=="pt") echo " selected"; ?>>Portuguese - português</option>
                        <option value="ru"<?php if(isset($to) && $to=="ru") echo " selected"; ?>>Russian - русский</option>
                        <option value="zh-CN"<?php if(isset($to) && $to=="zh-CN") echo " selected"; ?>>Chinese - 中文</option>
                        <option value="ja"<?php if(isset($to) && $to=="ja") echo " selected"; ?>>Japanese - 日本語</option>
                        <option value="ko"<?php if(isset($to) && $to=="ko") echo " selected"; ?>>Korean - 한국어</option>
                        <option value="ar"<?php if(isset($to) && $to=="ar") echo " selected"; ?>>Arabic - العربية</option>
                        <option value="hi"<?php if(isset($to) && $to=="hi") echo " selected"; ?>>Hindi - हिन्दी</option>
                        <option value="bg"<?php if(isset($to) && $to=="bg") echo " selected"; ?>>Bulgarian - български</option>
                        <option value="ca"<?php if(isset($to) && $to=="ca") echo " selected"; ?>>Catalan - català</option>
                        <option value="hr"<?php if(isset($to) && $to=="hr") echo " selected"; ?>>Croatian - hrvatski</option>
                        <option value="cs"<?php if(isset($to) && $to=="cs") echo " selected"; ?>>Czech - čeština</option>
                        <option value="da"<?php if(isset($to) && $to=="da") echo " selected"; ?>>Danish - dansk</option>
                        <option value="nl"<?php if(isset($to) && $to=="nl") echo " selected"; ?>>Dutch - Nederlands</option>
                        <option value="fi"<?php if(isset($to) && $to=="fi") echo " selected"; ?>>Finnish - suomi</option>
                        <option value="el"<?php if(isset($to) && $to=="el") echo " selected"; ?>>Greek - Ελληνικά</option>
                        <option value="hu"<?php if(isset($to) && $to=="hu") echo " selected"; ?>>Hungarian - magyar</option>
                        <option value="id"<?php if(isset($to) && $to=="id") echo " selected"; ?>>Indonesian - Indonesia</option>
                        <option value="ms"<?php if(isset($to) && $to=="ms") echo " selected"; ?>>Malay - Bahasa Melayu</option>
                        <option value="nb"<?php if(isset($to) && $to=="nb") echo " selected"; ?>>Norwegian - norsk</option>
                        <option value="pl"<?php if(isset($to) && $to=="pl") echo " selected"; ?>>Polish - polski</option>
                        <option value="ro"<?php if(isset($to) && $to=="ro") echo " selected"; ?>>Romanian - română</option>
                        <option value="sk"<?php if(isset($to) && $to=="sk") echo " selected"; ?>>Slovak - slovenčina</option>
                        <option value="sl"<?php if(isset($to) && $to=="sl") echo " selected"; ?>>Slovenian - slovenščina</option>
                        <option value="sv"<?php if(isset($to) && $to=="sv") echo " selected"; ?>>Swedish - svenska</option>
                        <option value="ta"<?php if(isset($to) && $to=="ta") echo " selected"; ?>>Tamil - தமிழ்</option>
                        <option value="th"<?php if(isset($to) && $to=="th") echo " selected"; ?>>Thai - ไทย</option>
                        <option value="tr"<?php if(isset($to) && $to=="tr") echo " selected"; ?>>Turkish - Türkçe</option>
                        <option value="vi"<?php if(isset($to) && $to=="vi") echo " selected"; ?>>Vietnamese - Tiếng Việt</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-language"></i> Translate Text
                </button>
            </form>
            
            <?php if(isset($txtTo) && $txtTo): ?>
            <div class="results fade-in-up">
                <h3>Translation Result</h3>
                <div class="result-text"><?php echo htmlspecialchars($txtTo); ?></div>
                <div class="download-links">
                    <a href="<?php echo $target_file_to; ?>" download="Translation.txt" class="btn btn-outline">
                        <i class="fas fa-download"></i> Download Translation
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Speech Section -->
    <section id="speech" class="conversion-section">
        <div class="conversion-container">
            <div class="section-header fade-in-up">
                <h2>Text to Speech Conversion</h2>
                <p>Listen to your text in multiple languages with natural-sounding voices</p>
            </div>
            
            <?php if (isset($_POST['txt']) && $_POST['txt']): ?>
            <div class="results fade-in-up">
                <h3>Audio Conversion</h3>
                
                <?php
                $urlFrom = post_request('https://www.text-konvertierung.de/audio_download.php', array("hl"=>$speech_arr[$from], "src"=>strip_tags($txt)));
                $urlTo = post_request('https://www.text-konvertierung.de/audio_download.php', array("hl"=>$speech_arr[$to], "src"=>strip_tags($txtTo)));
                ?>
                
                <div class="audio-player">
                    <h4>Original Text (<?php echo $from; ?>)</h4>
                    <audio controls>
                        <source src="<?php echo $urlFrom; ?>" type="audio/wav">
                        Your browser does not support the audio element.
                    </audio>
                </div>
                
                <div class="audio-player">
                    <h4>Translated Text (<?php echo $to; ?>)</h4>
                    <audio controls>
                        <source src="<?php echo $urlTo; ?>" type="audio/wav">
                        Your browser does not support the audio element.
                    </audio>
                </div>
                
                <div class="download-links">
                    <a href="<?php echo $urlFrom ?>" class="btn btn-outline" download="Audio_<?php echo $from ?>.wav">
                        <i class="fas fa-download"></i> Download <?php echo strtoupper($from); ?> Audio
                    </a>
                    <a href="<?php echo $urlTo ?>" class="btn btn-outline" download="Audio_<?php echo $to ?>.wav">
                        <i class="fas fa-download"></i> Download <?php echo strtoupper($to); ?> Audio
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Steps Section -->
    <section id="about" class="steps">
        <div class="steps-container">
            <div class="section-header fade-in-up">
                <h2>How It Works</h2>
                <p>Simple steps to convert your texts with professional results</p>
            </div>
            <div class="steps-grid">
                <div class="step-card fade-in-up">
                    <div class="step-number">1</div>
                    <h3>Upload or Enter Text</h3>
                    <p>Upload your documents, images, or simply type the text you want to convert.</p>
                </div>
                <div class="step-card fade-in-up">
                    <div class="step-number">2</div>
                    <h3>Choose Conversion Type</h3>
                    <p>Select between OCR text recognition, language translation, or text-to-speech conversion.</p>
                </div>
                <div class="step-card fade-in-up">
                    <div class="step-number">3</div>
                    <h3>Select Languages</h3>
                    <p>Choose your source and target languages from our extensive language support.</p>
                </div>
                <div class="step-card fade-in-up">
                    <div class="step-number">4</div>
                    <h3>Get Results</h3>
                    <p>Download your converted text or audio files instantly with professional quality.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials">
        <div class="testimonials-container">
            <div class="section-header fade-in-up">
                <h2>What Our Users Say</h2>
                <p>Trusted by professionals worldwide for accurate text conversion</p>
            </div>
            <div class="testimonials-grid">
                <div class="testimonial-card fade-in-up">
                    <div class="testimonial-header">
                        <div class="testimonial-avatar">JD</div>
                        <div class="testimonial-info">
                            <h4>John Doe</h4>
                            <span>CEO, Company ABC</span>
                        </div>
                    </div>
                    <p class="testimonial-text">"The text to speech feature is a game-changer for us. It helps us create audio versions of our content easily and professionally."</p>
                </div>
                <div class="testimonial-card fade-in-up">
                    <div class="testimonial-header">
                        <div class="testimonial-avatar">JS</div>
                        <div class="testimonial-info">
                            <h4>Jane Smith</h4>
                            <span>Marketing Manager, XYZ Inc.</span>
                        </div>
                    </div>
                    <p class="testimonial-text">"As a writer, I often need to translate texts, and this tool has made the process so much smoother. Highly recommended!"</p>
                </div>
                <div class="testimonial-card fade-in-up">
                    <div class="testimonial-header">
                        <div class="testimonial-avatar">DJ</div>
                        <div class="testimonial-info">
                            <h4>David Johnson</h4>
                            <span>Freelance Writer</span>
                        </div>
                    </div>
                    <p class="testimonial-text">"I use the OCR feature frequently for my research projects, and it has saved me a lot of time. Thank you for this amazing tool!"</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Text-Konvertierung</h3>
                    <p>Professional text conversion tools for OCR, translation, and speech synthesis. Making text processing simple and efficient.</p>
                </div>
                <div class="footer-section">
                    <h3>Services</h3>
                    <p><a href="#ocr">OCR Text Recognition</a></p>
                    <p><a href="#translation">Language Translation</a></p>
                    <p><a href="#speech">Text to Speech</a></p>
                </div>
                <div class="footer-section">
                    <h3>Contact</h3>
                    <p><a href="mailto:info@dominic-bilke.de">info@dominic-bilke.de</a></p>
                    <p><a href="https://www.dominic-bilke.de" target="_blank">www.dominic-bilke.de</a></p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Bilke Web and Software Development. All rights reserved.</p>
                <p>
                    <a href="https://www.dominic-bilke.de/en/privacy-policy" target="_blank">Privacy Policy</a> | 
                    <a href="https://www.dominic-bilke.de/en/terms-of-service" target="_blank">Terms of Service</a> | 
                    <a href="https://www.dominic-bilke.de/en/imprint" target="_blank">Imprint</a>
                </p>
            </div>
        </div>
    </footer>

    <!-- Modern JavaScript -->
    <script src="assets/js/modern.js"></script>
</body>
</html>
