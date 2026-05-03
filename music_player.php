<!-- ================================================
     Ambient Music Player (YouTube IFrame API)
     Include this file in every page's <body>.
     The YouTube video plays hidden; only the
     mute/volume controls are visible.
================================================ -->

<!-- Hidden YouTube iframe container -->
<div id="yt-player-wrap" style="position:fixed;width:1px;height:1px;overflow:hidden;opacity:0;pointer-events:none;bottom:0;left:0;"></div>

<!-- Floating music control widget -->
<div id="music-widget">
    <button id="music-toggle" title="Mute / Unmute">
        <span id="music-icon">🔊</span>
    </button>
    <input type="range" id="music-volume" min="0" max="100" value="30" title="Volume">
</div>

<style>
    #music-widget {
        position: fixed;
        bottom: 22px;
        right: 22px;
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 8px;
        background: rgba(0,0,0,0.72);
        border: 1px solid rgba(255,193,7,0.45);
        border-radius: 50px;
        padding: 7px 14px 7px 8px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.5);
        backdrop-filter: blur(6px);
    }

    #music-toggle {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.2rem;
        line-height: 1;
        padding: 0 2px;
        transition: transform 0.15s;
    }
    #music-toggle:hover { transform: scale(1.15); }

    #music-volume {
        -webkit-appearance: none;
        appearance: none;
        width: 80px;
        height: 4px;
        border-radius: 2px;
        outline: none;
        cursor: pointer;
    }
    #music-volume::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 13px;
        height: 13px;
        border-radius: 50%;
        background: #ffc107;
        cursor: pointer;
        box-shadow: 0 0 4px rgba(0,0,0,0.4);
    }
    #music-volume::-moz-range-thumb {
        width: 13px;
        height: 13px;
        border: none;
        border-radius: 50%;
        background: #ffc107;
        cursor: pointer;
    }
</style>

<script>
(function () {
    const VIDEO_ID    = 'MwD5Ps_lVz0';
    const STORAGE_KEY = 'jrs_music';

    // Restore saved prefs
    let saved = {};
    try { saved = JSON.parse(localStorage.getItem(STORAGE_KEY)) || {}; } catch(e) {}

    let volume = saved.volume !== undefined ? saved.volume : 30;
    let muted  = saved.muted  !== undefined ? saved.muted  : false;
    let player = null;
    let ready  = false;

    const toggleBtn = document.getElementById('music-toggle');
    const icon      = document.getElementById('music-icon');
    const slider    = document.getElementById('music-volume');

    slider.value = volume;
    updateSliderFill(volume);
    updateIcon();

    // Load YouTube IFrame API
    const tag = document.createElement('script');
    tag.src = 'https://www.youtube.com/iframe_api';
    document.head.appendChild(tag);

    window.onYouTubeIframeAPIReady = function () {
        player = new YT.Player('yt-player-wrap', {
            videoId: VIDEO_ID,
            playerVars: {
                autoplay:       1,
                loop:           1,
                playlist:       VIDEO_ID,
                controls:       0,
                disablekb:      1,
                fs:             0,
                modestbranding: 1,
                rel:            0,
            },
            events: {
                onReady: function (e) {
                    ready = true;
                    e.target.setVolume(muted ? 0 : volume);
                    e.target.playVideo();
                }
            }
        });
    };

    // Toggle mute
    toggleBtn.addEventListener('click', function () {
        muted = !muted;
        applyVolume();
        savePrefs();
    });

    // Volume slider
    slider.addEventListener('input', function () {
        volume = parseInt(this.value, 10);
        if (volume > 0) muted = false;
        applyVolume();
        savePrefs();
    });

    function applyVolume() {
        updateIcon();
        updateSliderFill(muted ? 0 : volume);
        if (ready && player) player.setVolume(muted ? 0 : volume);
    }

    function updateIcon() {
        if (muted || volume === 0)  icon.textContent = '🔇';
        else if (volume < 50)       icon.textContent = '🔉';
        else                        icon.textContent = '🔊';
    }

    function updateSliderFill(val) {
        slider.style.background =
            `linear-gradient(to right, #ffc107 0%, #ffc107 ${val}%, rgba(255,255,255,0.2) ${val}%)`;
    }

    function savePrefs() {
        try { localStorage.setItem(STORAGE_KEY, JSON.stringify({ volume, muted })); } catch(e) {}
    }
})();
</script>
