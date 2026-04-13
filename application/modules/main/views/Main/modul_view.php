<style>
  /* ── Scoped to #mv-wrap ─────────────────────────────────── */
  #mv-wrap {
    padding: 16px 4px 32px;
    font-family: 'Segoe UI', system-ui, Arial, sans-serif;
  }

  /* Search */
  #mv-search-wrap { margin-bottom: 20px; }
  #mv-search {
    width: 100%;
    max-width: 100%;
    padding: 9px 14px 9px 38px;
    border: 1.5px solid #dde3ec;
    border-radius: 24px;
    font-size: 13px;
    color: #1a202c;
    background: #f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E") 11px center / 15px no-repeat;
    outline: none;
    transition: border-color .18s, box-shadow .18s;
  }
  #mv-search:focus {
    border-color: #00669F;
    box-shadow: 0 0 0 3px rgba(0,102,159,0.1);
    background-color: #fff;
  }
  #mv-search::placeholder { color: #94a3b8; }

  /* Group */
  .mv-group { margin-bottom: 28px; }
  .mv-group.mv-hidden { display: none; }
  .mv-group-hdr {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 14px;
    padding-bottom: 8px;
    border-bottom: 1.5px solid #e8edf4;
  }
  .mv-group-bar { width: 4px; height: 20px; border-radius: 3px; flex-shrink: 0; }
  .mv-group-label {
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: #4a5568;
  }
  .mv-group-count {
    background: #eef2f7;
    color: #94a3b8;
    font-size: 11px;
    font-weight: 600;
    padding: 1px 8px;
    border-radius: 10px;
  }

  /* Grid */
  .mv-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 12px;
  }

  /* Card */
  @keyframes mv-fadein {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .mv-card {
    background: #fff;
    border: 1.5px solid #e8edf2;
    border-radius: 14px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    padding: 20px 10px 14px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: #4a5568;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    animation: mv-fadein .3s ease both;
    transition: transform .2s, box-shadow .2s, border-color .2s, color .2s;
  }
  .mv-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 3px;
    background: var(--mc, #00669F);
    transform: scaleX(0);
    transition: transform .2s;
    transform-origin: center;
  }
  .mv-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 22px rgba(0,0,0,0.12);
    border-color: var(--mc, #00669F);
    color: var(--mc, #00669F);
    text-decoration: none;
  }
  .mv-card:hover::after { transform: scaleX(1); }
  .mv-card:hover .mv-icon { transform: scale(1.1) rotate(-4deg); }

  .mv-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #fff;
    flex-shrink: 0;
    transition: transform .2s;
    background: linear-gradient(145deg, var(--mc, #00669F), var(--mc2, #0a2d5a));
  }
  .mv-name {
    font-size: 10.5px;
    font-weight: 700;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    line-height: 1.4;
    word-break: break-word;
    color: inherit;
  }
  .mv-ext {
    position: absolute;
    top: 7px; right: 8px;
    font-size: 9px;
    color: #cbd5e0;
  }

  /* No result */
  #mv-noresult {
    display: none;
    text-align: center;
    padding: 40px;
    color: #94a3b8;
    font-size: 14px;
  }
  #mv-noresult i { font-size: 32px; display: block; margin-bottom: 10px; }

  @media (max-width: 768px) {
    .mv-grid { grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; }
    .mv-icon  { width: 44px; height: 44px; font-size: 18px; }
  }
  @media (max-width: 480px) {
    .mv-grid { grid-template-columns: repeat(3, 1fr); }
  }
</style>

<div id="mv-wrap">

  <!-- Search -->
  <div id="mv-search-wrap">
    <input type="text" id="mv-search" placeholder="Cari modul..." autocomplete="off">
  </div>

  <?php
    $palettes = [
      ['#2196F3','#0D47A1'], ['#43A047','#1B5E20'], ['#FB8C00','#BF360C'],
      ['#E91E63','#880E4F'], ['#8E24AA','#4A148C'], ['#00ACC1','#006064'],
      ['#F4511E','#BF360C'], ['#3949AB','#1A237E'], ['#00897B','#004D40'],
      ['#FDD835','#F57F17'], ['#039BE5','#01579B'], ['#6D4C41','#3E2723'],
      ['#546E7A','#263238'], ['#E53935','#B71C1C'], ['#7CB342','#33691E'],
      ['#00ACC1','#006064'],
    ];
    $g_idx = 0;
    foreach ($modul as $key_row => $rows_m) :
      $p      = $palettes[$g_idx % count($palettes)];
      $g_color = $p[0];
      $g_idx++;
      $m_idx  = 0;
  ?>
  <div class="mv-group" data-mvgroup>
    <div class="mv-group-hdr">
      <div class="mv-group-bar" style="background:<?php echo $g_color?>"></div>
      <span class="mv-group-label"><?php echo strip_tags($rows_m['group_modul_name'])?></span>
      <span class="mv-group-count"><?php echo count($rows_m['modul'])?></span>
    </div>
    <div class="mv-grid">
      <?php foreach ($rows_m['modul'] as $row_modul) :
        $mp = $palettes[$m_idx % count($palettes)];
        $m_idx++;
        if ($row_modul->is_new_tab == 'N') {
          $href   = base_url().'dashboard?mod='.$row_modul->modul_id;
          $target = '';
        } else {
          $href   = $row_modul->link_on_new_tab;
          $target = 'target="_blank" rel="noopener"';
        }
      ?>
      <a class="mv-card"
         href="<?php echo $href?>" <?php echo $target?>
         style="--mc:<?php echo $mp[0]?>;--mc2:<?php echo $mp[1]?>"
         data-mvname="<?php echo strtolower(strip_tags($row_modul->name))?>">
        <?php if ($row_modul->is_new_tab == 'Y'): ?>
          <i class="mv-ext fa fa-external-link"></i>
        <?php endif; ?>
        <div class="mv-icon">
          <i class="<?php echo $row_modul->icon?>"></i>
        </div>
        <div class="mv-name"><?php echo strip_tags(strtoupper($row_modul->name))?></div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endforeach; ?>

  <div id="mv-noresult">
    <i class="fa fa-search"></i>
    Tidak ditemukan modul yang cocok.
  </div>

</div><!-- /#mv-wrap -->

<script>
  /* Stagger animation */
  document.querySelectorAll('.mv-card').forEach(function (el, i) {
    el.style.animationDelay = (i * 0.025) + 's';
  });

  /* Search filter */
  document.getElementById('mv-search').addEventListener('input', function () {
    var q = this.value.trim().toLowerCase();
    var cards  = document.querySelectorAll('.mv-card');
    var groups = document.querySelectorAll('[data-mvgroup]');
    var any = false;

    cards.forEach(function (c) {
      c.style.display = (!q || (c.dataset.mvname || '').indexOf(q) !== -1) ? '' : 'none';
    });

    groups.forEach(function (g) {
      var vis = g.querySelectorAll('.mv-card:not([style*="display: none"])').length > 0;
      g.classList.toggle('mv-hidden', !vis);
      if (vis) any = true;
    });

    document.getElementById('mv-noresult').style.display = (any || !q) ? 'none' : 'block';
  });
</script>
