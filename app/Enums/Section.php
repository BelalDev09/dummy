<?php

declare(strict_types=1);

namespace App\Enums;

use App\Models\Brand;
use App\Models\Video;
use BenSampo\Enum\Enum;
use GuzzleHttp\Psr7\Header;

use Livewire\Attributes\Title;
use function Livewire\of;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Section extends Enum
{
    // Home Page -
    const TopSection = 'top_section';
    const CategorySection = 'category_section';
    const MenCollectionSection = 'men_collection_section';
    const WomenCollectionSection = 'women_collection_section';
    const WatchSection = 'watch_section';

    const HighTechSection = 'high_tech_section';
    const AboutSection = 'about_section';
    const BlogSection = 'blog_section';
    const MessageSection = 'message_section';
    // About Us Section
    const MedilockSection = 'medilock_section';
    const DifferentSection = 'different_section';
    const SchduleSection = 'schdule_section';

    // buyer page
    const BuyerTopSection = 'buyer_top_section';
    const BuyerbuyingJourneySection = 'buyer_buying_journey_section';
    const BuyerGuideSection = 'buyer_guide_section';

    // landing  partner page
    const LandingTopSection = 'landing_top_section';
    const PartnerAboutSection = 'landing_partner_about_section';
    const LandingLoanSection = 'landing_loan_section';

    // seller page
    const SellerTopSection =  'seller_top_section';
    const SellerSellingProcessSection = 'seller_selling_process_section';
    const SellerGuideSection = 'seller_guide_section';
}
