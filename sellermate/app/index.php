<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SellerMate AI — Local product listing assistant</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar navbar-dark bg-dark"><div class="container"><a class="navbar-brand" href="/">🦥 Slow &amp; Steady AI</a><div class="suite-nav"><a href="/">LazyMate</a><a href="/sellermate/" aria-current="page">SellerMate</a></div></div></nav>
<main class="container py-4 py-lg-5">
  <section class="hero p-4 p-lg-5 mb-4">
    <p class="eyebrow">SLOW AND STEADY · OPEN-SOURCE AI</p>
    <h1>Write better product listings.<br><span>Keep your data at home.</span></h1>
    <p class="lead mb-0">Turn product facts into Bangla and English e-commerce copy using a model running on your own machine.</p>
    <div class="privacy-note mt-3">🔒 After setup and model download, generation runs locally. No hosted AI API is required.</div>
  </section>
  <div class="row g-4">
    <section class="col-lg-7">
      <div class="panel p-4">
        <h2 class="h4 mb-3">Product details</h2>
        <form id="listing-form">
          <div class="mb-3"><label for="product_name" class="form-label">Product name <span class="required">*</span></label><input class="form-control" id="product_name" maxlength="160" required placeholder="e.g. 4G Solar Security Camera"></div>
          <div class="row g-3">
            <div class="col-md-6"><label for="category" class="form-label">Category</label><input class="form-control" id="category" maxlength="100" placeholder="CCTV camera"></div>
            <div class="col-md-6"><label for="price" class="form-label">Price (optional)</label><input class="form-control" id="price" maxlength="40" placeholder="৳ 2,500"></div>
          </div>
          <div class="mt-3"><label for="features" class="form-label">Known features / specifications <span class="required">*</span></label><textarea class="form-control" id="features" rows="4" maxlength="3000" required placeholder="4G SIM, solar panel, two lenses, night vision. Only include facts you can verify."></textarea><div class="form-text">Include only verified facts. The assistant is instructed not to invent specifications.</div></div>
          <div class="mt-3"><label for="keywords" class="form-label">SEO keywords (optional)</label><input class="form-control" id="keywords" maxlength="300" placeholder="4G CCTV camera, solar security camera"></div>
          <div class="row g-3 mt-1">
            <div class="col-md-6"><label for="language" class="form-label">Output language</label><select class="form-select" id="language"><option value="Bangla">Bangla</option><option value="English">English</option><option value="Both">Bangla + English</option></select></div>
            <div class="col-md-6"><label for="tone" class="form-label">Tone</label><select class="form-select" id="tone"><option value="clear and helpful">Clear and helpful</option><option value="friendly and persuasive">Friendly and persuasive</option><option value="professional">Professional</option><option value="concise">Concise</option></select></div>
          </div>
          <button class="btn btn-primary w-100 mt-4" id="generate-btn" type="submit">Generate my listing <span aria-hidden="true">→</span></button>
          <p class="small text-secondary mt-2 mb-0">Your input is sent only to the AI service on this machine. Avoid entering customer or other sensitive personal data.</p>
        </form>
      </div>
    </section>
    <section class="col-lg-5">
      <div class="panel p-4 h-100">
        <div class="d-flex justify-content-between align-items-center gap-2 mb-3"><h2 class="h4 mb-0">Generated listing</h2><span id="status" class="status">Waiting</span></div>
        <div id="error" class="alert alert-danger d-none" role="alert"></div>
        <div id="placeholder" class="empty-state"><div class="empty-icon">✍️</div><h3 class="h5">Your listing will appear here</h3><p class="mb-0">Add product facts and let your local AI do the first draft.</p></div>
        <div id="result" class="d-none">
          <div class="result-block"><div class="result-heading">SEO title</div><div id="out-title" class="result-content"></div><button class="btn btn-sm btn-outline-secondary copy-btn" data-copy="out-title">Copy</button></div>
          <div class="result-block"><div class="result-heading">Short description</div><div id="out-short" class="result-content"></div><button class="btn btn-sm btn-outline-secondary copy-btn" data-copy="out-short">Copy</button></div>
          <div class="result-block"><div class="result-heading">Full description</div><div id="out-long" class="result-content preserve-lines"></div><button class="btn btn-sm btn-outline-secondary copy-btn" data-copy="out-long">Copy</button></div>
          <div class="result-block"><div class="result-heading">SEO meta description</div><div id="out-meta" class="result-content"></div><button class="btn btn-sm btn-outline-secondary copy-btn" data-copy="out-meta">Copy</button></div>
          <div class="result-block"><div class="result-heading">Social caption</div><div id="out-social" class="result-content preserve-lines"></div><button class="btn btn-sm btn-outline-secondary copy-btn" data-copy="out-social">Copy</button></div>
          <div class="result-block"><div class="result-heading">Suggested keywords</div><div id="out-keywords" class="result-content"></div><button class="btn btn-sm btn-outline-secondary copy-btn" data-copy="out-keywords">Copy</button></div>
          <button id="copy-all" class="btn btn-outline-primary w-100 mt-2">Copy all fields</button>
          <p class="small text-secondary mt-3 mb-0">Review every claim before publishing. AI output may be inaccurate.</p>
        </div>
      </div>
    </section>
  </div>
  <footer class="text-center text-secondary small mt-5">Built for the DEV Community “Slow and Steady” challenge · Open models, local control.</footer>
</main>
<script src="app.js"></script>
</body></html>
