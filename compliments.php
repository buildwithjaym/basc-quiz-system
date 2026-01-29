<?php
// compliments.php
function random_compliment(){
  $c = [
    "🌟 Nice one! Your brain is in engineer mode today.",
    "🧠 Clean work! That was a very ‘software engineer’ finish.",
    "✨ Great job — you kept going like a pro.",
    "🚀 You’re leveling up fast. Keep it up!",
    "😼 That was smooth. You understood more than you think.",
    "💡 Solid attempt! Your logic is getting sharper.",
    "🏆 Respect! You handled the exam pressure well.",
    "🔥 Nice! Your effort is giving ‘top student’ energy.",
    "🌈 Good work — practice like this makes you unstoppable.",
    "🐣 Cute win! You finished the whole exam. That’s discipline!",
    "🛠️ Engineer mindset unlocked. Well done!",
    "📈 You’re improving — and it shows!",
    "🧩 Strong focus. Your future self will thank you.",
    "🎯 Great accuracy vibe. Keep practicing!",
    "😺 Proud of you! That was a solid run.",
    "💪 Good grind — that’s how skills grow.",
    "⭐ Nice! You’re closer to mastery than you think.",
    "🧃 Smooth finish! You kept your momentum.",
    "🧠💥 Brain power! You did great.",
    "🍀 Lucky? No... that was hard work. Good job!"
  ];
  return $c[array_rand($c)];
}
