<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connection Interrupted | UnlockRentals</title>
    
    {{-- Premium Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Outfit:wght@600;800&display=swap" rel="stylesheet">
    
    {{-- Icons --}}
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/style.css">
    
    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#2563EB',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        .card-inner {
            transition: transform 0.6s;
            transform-style: preserve-3d;
        }
        .card-flipped .card-inner {
            transform: rotateY(180deg);
        }
        .card-front, .card-back {
            backface-visibility: hidden;
        }
        .card-back {
            transform: rotateY(180deg);
        }
    </style>
</head>
<body class="min-h-screen bg-slate-900 text-slate-100 flex flex-col justify-between font-sans antialiased selection:bg-blue-500/30">

    {{-- Header --}}
    <header class="w-full max-w-7xl mx-auto px-6 py-6 flex items-center justify-between border-b border-white/5">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center font-bold text-white shadow-lg shadow-blue-500/20">U</div>
            <span class="font-outfit font-extrabold text-lg tracking-tight">Unlock<span class="text-blue-500">Rental</span></span>
        </div>
        <div class="flex items-center gap-2 text-xs bg-red-500/10 border border-red-500/20 text-red-400 px-3 py-1.5 rounded-full font-semibold">
            <span class="w-2 h-2 bg-red-500 rounded-full animate-ping"></span>
            Offline Mode
        </div>
    </header>

    {{-- Main Fallback Container --}}
    <main class="flex-1 flex flex-col items-center justify-center max-w-lg mx-auto px-6 py-12 text-center">
        
        {{-- Status Illustration --}}
        <div class="w-20 h-20 rounded-full bg-blue-600/10 border border-blue-500/20 flex items-center justify-center text-blue-500 mb-6 animate-bounce shadow-lg shadow-blue-500/5">
            <i class="ph ph-wifi-slash text-4xl"></i>
        </div>

        <h1 class="font-outfit font-extrabold text-3xl tracking-tight text-white mb-3">Connection Lost</h1>
        <p class="text-sm text-zinc-400 leading-relaxed mb-8">
            You are currently offline. Check your network connection or try reloading the page. In the meantime, test your memory with the challenge below!
        </p>

        {{-- Interactive Memory Matching Game --}}
        <div class="w-full bg-slate-950/60 backdrop-blur-md border border-white/5 rounded-2xl p-5 shadow-2xl mb-8">
            <div class="flex justify-between items-center mb-4">
                <span class="text-xs font-bold text-zinc-400 tracking-wider uppercase">Property Matcher</span>
                <span class="text-xs font-bold text-blue-500" id="moves-counter">Moves: 0</span>
            </div>

            {{-- 4x4 Grid --}}
            <div class="grid grid-cols-4 gap-3" id="game-grid">
                {{-- Loaded by JS --}}
            </div>

            {{-- Win Announcement --}}
            <div id="win-message" class="hidden mt-4 p-3 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-emerald-400 text-xs font-semibold animate-pulse">
                🎉 Perfect Match! All properties unlocked! Play again below.
            </div>

            <button onclick="resetGame()" class="mt-4 text-[11px] font-bold text-zinc-500 hover:text-zinc-300 transition-colors uppercase tracking-wider">
                <i class="ph ph-arrows-clockwise mr-1"></i> Restart Puzzle
            </button>
        </div>

        {{-- Retry Button --}}
        <button onclick="window.location.reload()" class="w-full sm:w-auto px-8 py-3.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl shadow-lg shadow-blue-600/20 transform active:scale-95 transition-all duration-200">
            <i class="ph ph-arrows-clockwise mr-2"></i> Check Connection
        </button>

    </main>

    {{-- Footer --}}
    <footer class="w-full text-center py-6 text-xs text-zinc-600 border-t border-white/5">
        &copy; 2026 UnlockRentals. Play offline, explore online.
    </footer>

    {{-- Game JavaScript --}}
    <script>
        const cardsData = [
            { id: 1, icon: 'ph-house-line', name: 'House' },
            { id: 2, icon: 'ph-key', name: 'Key' },
            { id: 3, icon: 'ph-buildings', name: 'Shop' },
            { id: 4, icon: 'ph-map-pin', name: 'Pin' },
            { id: 5, icon: 'ph-bed', name: 'Bed' },
            { id: 6, icon: 'ph-tag', name: 'Tag' },
            { id: 7, icon: 'ph-star', name: 'Star' },
            { id: 8, icon: 'ph-lock-key', name: 'Lock' }
        ];

        // Double array to make pairs
        let cards = [...cardsData, ...cardsData];
        let flippedCards = [];
        let matchedPairs = 0;
        let moves = 0;
        let canPlay = true;

        const grid = document.getElementById('game-grid');
        const movesCounter = document.getElementById('moves-counter');
        const winMessage = document.getElementById('win-message');

        function shuffle(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
            return array;
        }

        function buildBoard() {
            grid.innerHTML = '';
            shuffle(cards);
            cards.forEach((card, index) => {
                const cardEl = document.createElement('div');
                cardEl.className = 'aspect-square relative cursor-pointer group';
                cardEl.dataset.index = index;
                cardEl.dataset.id = card.id;

                cardEl.innerHTML = `
                    <div class="card-inner w-full h-full relative rounded-xl border border-white/5 bg-slate-900 flex items-center justify-center">
                        <!-- Card Front (Hidden by default) -->
                        <div class="card-front absolute inset-0 bg-blue-600/10 flex items-center justify-center text-blue-500 rounded-xl">
                            <i class="ph-bold ${card.icon} text-xl"></i>
                        </div>
                        <!-- Card Back (Visible initially) -->
                        <div class="card-back absolute inset-0 bg-slate-800 flex items-center justify-center text-zinc-600 rounded-xl group-hover:bg-slate-700 transition-colors">
                            <i class="ph ph-question text-lg font-bold text-zinc-500"></i>
                        </div>
                    </div>
                `;

                cardEl.addEventListener('click', () => flipCard(cardEl));
                grid.appendChild(cardEl);
            });
        }

        function flipCard(cardEl) {
            if (!canPlay || cardEl.classList.contains('card-flipped') || flippedCards.length >= 2) return;

            cardEl.classList.add('card-flipped');
            flippedCards.push(cardEl);

            if (flippedCards.length === 2) {
                moves++;
                movesCounter.textContent = `Moves: ${moves}`;
                checkMatch();
            }
        }

        function checkMatch() {
            canPlay = false;
            const [c1, c2] = flippedCards;

            if (c1.dataset.id === c2.dataset.id) {
                // Match
                matchedPairs++;
                flippedCards = [];
                canPlay = true;

                // Add success effect
                c1.querySelector('.card-inner').classList.add('border-emerald-500/40', 'bg-emerald-500/5');
                c2.querySelector('.card-inner').classList.add('border-emerald-500/40', 'bg-emerald-500/5');

                if (matchedPairs === cardsData.length) {
                    winMessage.classList.remove('hidden');
                }
            } else {
                // Mismatch
                setTimeout(() => {
                    c1.classList.remove('card-flipped');
                    c2.classList.remove('card-flipped');
                    flippedCards = [];
                    canPlay = true;
                }, 1000);
            }
        }

        function resetGame() {
            flippedCards = [];
            matchedPairs = 0;
            moves = 0;
            canPlay = true;
            movesCounter.textContent = 'Moves: 0';
            winMessage.classList.add('hidden');
            buildBoard();
        }

        // Auto reload if network comes back online
        window.addEventListener('online', () => {
            window.location.reload();
        });

        // Init
        buildBoard();
    </script>
</body>
</html>
