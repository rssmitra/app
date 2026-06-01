<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Akses Tidak Valid</title>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: 'Segoe UI', system-ui, sans-serif;
    background: #f1f5f9; display: flex; align-items: center; justify-content: center;
    min-height: 100vh; padding: 20px;
  }
  .error-card {
    background: #fff; border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,.1);
    padding: 36px 40px; text-align: center; max-width: 400px; width: 100%;
  }
  .error-icon { font-size: 52px; margin-bottom: 14px; }
  .error-title { font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 8px; }
  .error-message {
    font-size: 13px; color: #475569; line-height: 1.7;
    background: #f8fafc; border-radius: 6px;
    padding: 10px 14px; margin: 12px 0; text-align: left;
    border-left: 3px solid #ef4444;
  }
  .error-hint { font-size: 12px; color: #94a3b8; margin-top: 10px; line-height: 1.6; }
  .nomr-badge {
    display: inline-block; font-size: 11px; background: #f1f5f9;
    color: #475569; border: 1px solid #e2e8f0;
    border-radius: 4px; padding: 2px 8px; margin-top: 8px;
  }
</style>
</head>
<body>
  <div class="error-card">
    <div class="error-icon">&#9203;</div>
    <div class="error-title">Akses Tidak Valid atau Sesi Habis</div>
    <div class="error-message"><?php echo htmlspecialchars($message); ?></div>
    <?php if (!empty($nomr)): ?>
      <span class="nomr-badge">No. MR: <?php echo htmlspecialchars($nomr); ?></span>
    <?php endif; ?>
    <p class="error-hint">
      Link tampilan SOAP ini memiliki masa berlaku terbatas.<br>
      Silakan minta link baru melalui endpoint API <code>getSoapLink</code> dari aplikasi Anda.
    </p>
  </div>
</body>
</html>
