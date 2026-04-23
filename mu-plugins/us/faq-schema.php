<?php
/**
 * Add FAQ Schema to Careers and Contact pages - Harris Exploration
 */
add_action('wp_head', function() {
    if (!is_page(array('careers', 'contact-us'))) return;
    
    $page = get_queried_object();
    $title = get_the_title($page->ID);
    
    // FAQ Schema for Careers
    if (is_page('careers')) {
        $faqs = [
            [
                'question' => 'What drilling services does Harris Exploration offer?',
                'answer' => 'We offer expert drilling services including environmental, geotechnical, and mining drilling solutions. Our unwavering commitment to safety sets us apart in the industry.'
            ],
            [
                'question' => 'What qualifications are needed to join Harris Exploration?',
                'answer' => 'We seek dedicated professionals with drilling experience. Safety certifications, industry training, and a commitment to our safety-first culture are essential.'
            ],
            [
                'question' => 'What makes Harris Exploration different?',
                'answer' => 'Safety is our core value. We maintain rigorous safety standards, well-equipped crews, and modern drilling equipment to ensure project success and crew wellbeing.'
            ],
            [
                'question' => 'How do I apply for open positions?',
                'answer' => 'Contact our team through our careers page or reach out directly. Include your experience, certifications, and availability.'
            ]
        ];
    }
    // FAQ Schema for Contact
    elseif (is_page('contact-us')) {
        $faqs = [
            [
                'question' => 'What regions does Harris Exploration serve?',
                'answer' => 'We provide drilling services across Western Canada with a focus on environmental, geotechnical, and mining projects. Contact us to discuss your location.'
            ],
            [
                'question' => 'How can I request drilling services?',
                'answer' => 'Fill out our contact form or call us directly. Provide project details including location, scope, and timeline for a comprehensive quote.'
            ],
            [
                'question' => 'Do you offer free project assessments?',
                'answer' => 'Yes, we offer project assessments to determine drilling requirements and provide accurate quotes. Contact our team to schedule an evaluation.'
            ],
            [
                'question' => 'What project types do you specialize in?',
                'answer' => 'We specialize in environmental drilling, geotechnical investigations, mining support, and construction drilling projects throughout Western Canada.'
            ]
        ];
    }
    else {
        return;
    }
    
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => []
    ];
    
    foreach ($faqs as $faq) {
        $schema['mainEntity'][] = [
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $faq['answer']
            ]
        ];
    }
    
    echo '<script type="application/ld+json">' . json_encode($schema) . '</script>' . "\n";
}, 5);
