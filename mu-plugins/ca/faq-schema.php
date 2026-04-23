<?php
/**
 * Add FAQ Schema to Careers and Contact pages
 */
add_action('wp_head', function() {
    if (!is_page(array('careers', 'contact-us'))) return;
    
    $page = get_queried_object();
    $title = get_the_title($page->ID);
    
    // FAQ Schema for Careers
    if (is_page('careers')) {
        $faqs = [
            [
                'question' => 'What types of drilling services does Earth Drilling Co. Ltd. offer?',
                'answer' => 'We offer sonic drilling, auger drilling, dual rotary, mud rotary, Odex air rotary, Becker hammer, and cone penetration testing services for mining, environmental, and construction projects throughout Western Canada.'
            ],
            [
                'question' => 'What qualifications do I need to work at Earth Drilling?',
                'answer' => 'Qualifications vary by position. We seek experienced drillers, helpers, and field technicians. Safety certifications (H2S Alive, First Aid) and industry experience are assets. We provide ongoing training.'
            ],
            [
                'question' => 'What benefits do you offer employees?',
                'answer' => 'We offer competitive wages, comprehensive health benefits, retirement plans, and training opportunities. Our safety-first culture means well-maintained equipment and strong safety protocols.'
            ],
            [
                'question' => 'How do I apply for a position?',
                'answer' => 'Submit your resume through our contact form or email our HR team. Include your certifications, experience, and location preferences.'
            ]
        ];
    }
    // FAQ Schema for Contact
    elseif (is_page('contact-us')) {
        $faqs = [
            [
                'question' => 'What areas do you serve?',
                'answer' => 'We serve all of Western Canada including Alberta, Saskatchewan, Manitoba, and Northern British Columbia with drilling services for mining, environmental, and construction projects.'
            ],
            [
                'question' => 'How can I request a quote?',
                'answer' => 'Fill out our online request a quote form or call our Calgary office at 1-855-964-1538. Provide project details including location, depth requirements, and project type for an accurate quote.'
            ],
            [
                'question' => 'What information do you need for a quote?',
                'answer' => 'We need project location, drilling depth requirements, soil conditions if known, project type (mining, environmental, geotechnical, construction), timeline, and access information.'
            ],
            [
                'question' => 'Do you provide emergency drilling services?',
                'answer' => 'Yes, we offer responsive drilling services for time-sensitive projects. Contact our office to discuss urgent requirements and availability.'
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
