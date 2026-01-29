<?php

function get_question_bank() {

  $mcq = [
    [
      "key" => "mcq_01",
      "prompt" => "What is software engineering mainly about?",
      "choices" => [
        "A" => "Making hardware",
        "B" => "Designing, building, and maintaining software",
        "C" => "Selling computers",
        "D" => "Using the internet"
      ],
      "answer" => "B"
    ],
    [
      "key" => "mcq_02",
      "prompt" => "Why is software considered intangible?",
      "choices" => [
        "A" => "You can touch it",
        "B" => "It has weight",
        "C" => "It cannot be physically touched",
        "D" => "It is made of metal"
      ],
      "answer" => "C"
    ],
    [
      "key" => "mcq_03",
      "prompt" => "Why can software systems be very complex?",
      "choices" => [
        "A" => "They are heavy",
        "B" => "They have infinite possible complexity",
        "C" => "They never change",
        "D" => "They are cheap"
      ],
      "answer" => "B"
    ],
    [
      "key" => "mcq_04",
      "prompt" => "Do all software types use the same engineering techniques?",
      "choices" => [
        "A" => "Yes",
        "B" => "No",
        "C" => "Only games do",
        "D" => "Only websites do"
      ],
      "answer" => "B"
    ],
    [
      "key" => "mcq_05",
      "prompt" => "What increases the demand for software engineering?",
      "choices" => [
        "A" => "Smaller systems",
        "B" => "Slower computers",
        "C" => "Bigger and more complex systems",
        "D" => "Fewer users"
      ],
      "answer" => "C"
    ],
    [
      "key" => "mcq_06",
      "prompt" => "What can cause software projects to fail?",
      "choices" => [
        "A" => "High expectations",
        "B" => "Poor engineering methods",
        "C" => "Too many users",
        "D" => "Too much money"
      ],
      "answer" => "B"
    ],
    [
      "key" => "mcq_07",
      "prompt" => "Professional software is usually developed for:",
      "choices" => [
        "A" => "Personal use only",
        "B" => "Entertainment only",
        "C" => "Businesses or organizations",
        "D" => "Games only"
      ],
      "answer" => "C"
    ],
    [
      "key" => "mcq_08",
      "prompt" => "Software includes more than programs. It also includes:",
      "choices" => [
        "A" => "Hardware",
        "B" => "Documentation",
        "C" => "Monitors",
        "D" => "Printers"
      ],
      "answer" => "B"
    ],
    [
      "key" => "mcq_09",
      "prompt" => "Why is documentation important in professional software?",
      "choices" => [
        "A" => "For decoration",
        "B" => "To help users and developers understand the system",
        "C" => "To slow development",
        "D" => "To replace testing"
      ],
      "answer" => "B"
    ],
    [
      "key" => "mcq_10",
      "prompt" => "What tool is mentioned for managing software versions?",
      "choices" => [
        "A" => "GitHub",
        "B" => "Calculator",
        "C" => "Paint",
        "D" => "Browser"
      ],
      "answer" => "A"
    ],
    [
      "key" => "mcq_11",
      "prompt" => "Generic software products are:",
      "choices" => [
        "A" => "Made for one customer",
        "B" => "Sold to many customers",
        "C" => "Never reused",
        "D" => "Always free"
      ],
      "answer" => "B"
    ],
    [
      "key" => "mcq_12",
      "prompt" => "Customized software products are:",
      "choices" => [
        "A" => "Sold in stores",
        "B" => "Made for a specific customer",
        "C" => "Always online",
        "D" => "Only for games"
      ],
      "answer" => "B"
    ],
    [
      "key" => "mcq_13",
      "prompt" => "Which is an example of a stand-alone application?",
      "choices" => [
        "A" => "Word processor",
        "B" => "Car brake system",
        "C" => "Payroll batch system",
        "D" => "Embedded sensor"
      ],
      "answer" => "A"
    ],
    [
      "key" => "mcq_14",
      "prompt" => "Embedded systems are commonly found in:",
      "choices" => [
        "A" => "Books",
        "B" => "Cars and mobile phones",
        "C" => "Notebooks",
        "D" => "Websites only"
      ],
      "answer" => "B"
    ],
    [
      "key" => "mcq_15",
      "prompt" => "Batch processing systems are used for:",
      "choices" => [
        "A" => "Games",
        "B" => "Payroll and billing",
        "C" => "Web browsing",
        "D" => "Chatting"
      ],
      "answer" => "B"
    ],
    [
      "key" => "mcq_16",
      "prompt" => "A managed software process includes:",
      "choices" => [
        "A" => "No planning",
        "B" => "Planning and scheduling",
        "C" => "Ignoring requirements",
        "D" => "No deadlines"
      ],
      "answer" => "B"
    ],
    [
      "key" => "mcq_17",
      "prompt" => "Dependability includes which of the following?",
      "choices" => [
        "A" => "Design only",
        "B" => "Reliability and security",
        "C" => "Marketing",
        "D" => "Speed only"
      ],
      "answer" => "B"
    ],
    [
      "key" => "mcq_18",
      "prompt" => "What did the web change in software engineering?",
      "choices" => [
        "A" => "Software became offline",
        "B" => "Easier deployment and updates",
        "C" => "Fewer systems",
        "D" => "Less integration"
      ],
      "answer" => "B"
    ],
    [
      "key" => "mcq_19",
      "prompt" => "What does SaaS stand for?",
      "choices" => [
        "A" => "Software as a Service",
        "B" => "System as a Software",
        "C" => "Software and Security",
        "D" => "Service as a Software"
      ],
      "answer" => "A"
    ],
    [
      "key" => "mcq_20",
      "prompt" => "One ethical responsibility of software engineers is:",
      "choices" => [
        "A" => "Confidentiality",
        "B" => "Hacking",
        "C" => "Piracy",
        "D" => "Ignoring laws"
      ],
      "answer" => "A"
    ],
  ];

  
  $ident = [
    ["key"=>"ident_01","image"=>"assets/img/github.png","answer"=>"github"],
    ["key"=>"ident_02","image"=>"assets/img/gitlab.png","answer"=>"gitlab"],
    ["key"=>"ident_03","image"=>"assets/img/supabase.png","answer"=>"supabase"],
    ["key"=>"ident_04","image"=>"assets/img/mysql.png","answer"=>"mysql"],
    ["key"=>"ident_05","image"=>"assets/img/postgresql.png","answer"=>"postgresql"],
    ["key"=>"ident_06","image"=>"assets/img/firebase.png","answer"=>"firebase"],
    ["key"=>"ident_07","image"=>"assets/img/git.png","answer"=>"git"],
    ["key"=>"ident_08","image"=>"assets/img/microprocessor.png","answer"=>"microprocessor"],
    ["key"=>"ident_09","image"=>"assets/img/docker.png","answer"=>"docker"],
    ["key"=>"ident_10","image"=>"assets/img/vscode.png","answer"=>"vscode"],
  ];

  return ["mcq" => $mcq, "ident" => $ident];
}
