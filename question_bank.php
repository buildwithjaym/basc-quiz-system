<?php
// question_bank.php

function get_question_bank() {

  $mcq = [
    [
      "key" => "mcq_01",
      "prompt" => "The term ethics is derived from the Greek word ____.",
      "choices" => [
        "A" => "mos",
        "B" => "ethos",
        "C" => "moris",
        "D" => "moralitas"
      ],
      "answer" => "B"
    ],
    [
      "key" => "mcq_02",
      "prompt" => "Ethos originally means ____.",
      "choices" => [
        "A" => "law and punishment",
        "B" => "custom or character",
        "C" => "religion",
        "D" => "government rule"
      ],
      "answer" => "B"
    ],
    [
      "key" => "mcq_03",
      "prompt" => "Ethics is a branch of philosophy that studies the ____ of human action.",
      "choices" => [
        "A" => "speed",
        "B" => "rightness or wrongness",
        "C" => "popularity",
        "D" => "profit"
      ],
      "answer" => "B"
    ],
    [
      "key" => "mcq_04",
      "prompt" => "Morality refers to the set of standards that enable people to live ____ in groups.",
      "choices" => [
        "A" => "competitively",
        "B" => "cooperatively",
        "C" => "secretly",
        "D" => "alone"
      ],
      "answer" => "B"
    ],
    [
      "key" => "mcq_05",
      "prompt" => "Morality comes from the Greek/Latin roots meaning ____.",
      "choices" => [
        "A" => "manner or characteristics",
        "B" => "truth and science",
        "C" => "money and trade",
        "D" => "plants and animals"
      ],
      "answer" => "A"
    ],
    [
      "key" => "mcq_06",
      "prompt" => "Which area of ethics deals with the nature of moral judgement and the meaning of ethical principles?",
      "choices" => [
        "A" => "Applied Ethics",
        "B" => "Normative Ethics",
        "C" => "Metaethics",
        "D" => "Scientific Ethics"
      ],
      "answer" => "C"
    ],
    [
      "key" => "mcq_07",
      "prompt" => "Normative ethics is concerned with ____.",
      "choices" => [
        "A" => "the criteria for what is right or wrong",
        "B" => "computer programming rules",
        "C" => "weather patterns",
        "D" => "historical dates"
      ],
      "answer" => "A"
    ],
    [
      "key" => "mcq_08",
      "prompt" => "Applied ethics deals with ____.",
      "choices" => [
        "A" => "dream interpretation",
        "B" => "application of moral principles to real-world problems",
        "C" => "grammar rules",
        "D" => "math formulas"
      ],
      "answer" => "B"
    ],
    [
      "key" => "mcq_09",
      "prompt" => "Ethics helps people choose good actions and avoid ____ others.",
      "choices" => [
        "A" => "helping",
        "B" => "harming",
        "C" => "respecting",
        "D" => "listening to"
      ],
      "answer" => "B"
    ],
    [
      "key" => "mcq_10",
      "prompt" => "For society, ethics helps keep ____.",
      "choices" => [
        "A" => "peace and order",
        "B" => "more noise",
        "C" => "more confusion",
        "D" => "more fighting"
      ],
      "answer" => "A"
    ],
    [
      "key" => "mcq_11",
      "prompt" => "Ethics can provide a moral plan by acting like a ____ for living.",
      "choices" => [
        "A" => "roadmap",
        "B" => "calculator",
        "C" => "receipt",
        "D" => "coin"
      ],
      "answer" => "A"
    ],
    [
      "key" => "mcq_12",
      "prompt" => "Ethics does not always give one final answer because some problems are ____.",
      "choices" => [
        "A" => "simple",
        "B" => "complicated",
        "C" => "funny",
        "D" => "fake"
      ],
      "answer" => "B"
    ],
    [
      "key" => "mcq_13",
      "prompt" => "Ethics is about the “other” because it focuses on how your actions affect ____.",
      "choices" => [
        "A" => "only yourself",
        "B" => "other people",
        "C" => "only animals",
        "D" => "only machines"
      ],
      "answer" => "B"
    ],
    [
      "key" => "mcq_14",
      "prompt" => "Ethics can be a source of group strength because it builds ____.",
      "choices" => [
        "A" => "trust and unity",
        "B" => "fear and doubt",
        "C" => "hate and anger",
        "D" => "laziness"
      ],
      "answer" => "A"
    ],
    [
      "key" => "mcq_15",
      "prompt" => "Moral realism says that right and wrong are ____.",
      "choices" => [
        "A" => "just jokes",
        "B" => "real facts, not just opinions",
        "C" => "always changing every day",
        "D" => "only based on emotions"
      ],
      "answer" => "B"
    ],
  ];

  return ["mcq" => $mcq, "ident" => []];
}
