<?php

namespace App\Http\Controllers\Web\backend\CMS\HomePage;

use App\Enums\Page;
use App\Enums\Section;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cms\Home\HomePageCategorySectionRequest;
use App\Http\Requests\Cms\Home\HomePageHighTechSectionRequest;
use App\Http\Requests\Cms\Home\HomePageMenCollectionSectionRequest;
use App\Http\Requests\Cms\Home\HomePageTopSectionRequest;
use App\Http\Requests\Cms\Home\HomePageWatchesSectionRequest;
use App\Http\Requests\Cms\Home\HomePageWomenCollectionSectionRequest;
use App\Models\Category;
use App\Models\CMS;
use App\Models\Product;
use App\Models\SubCategory;
use App\Traits\apiresponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HomePageController extends Controller
{
    use apiresponse;

    // Top Section
    public function topSection()
    {
        $data = CMS::firstOrNew([
            'page' => Page::HomePage,
            'section' => Section::TopSection,
        ]);

        return view('backend.layout.cms.home_page.top_section', compact('data'));
    }

    public function topSectionUpdate(HomePageTopSectionRequest $request)
    {
        try {

            $data = CMS::firstOrCreate([
                'page' => Page::HomePage,
                'section' => Section::TopSection,
            ]);

            $oldGallery = collect($data->gallery)->filter()->values()->toArray();
            $newGallery = [];

            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $file) {
                    if ($file && $file->isValid()) {
                        $newGallery[] = $file->store('cms/top', 'public');
                    }
                }
            }

            $data->gallery = array_values(array_merge($oldGallery, $newGallery));

            $data->title = $request->title;
            $data->sub_title = $request->sub_title;
            $data->button_text = $request->button_text;
            $data->status = 'active';

            $data->save();

            return redirect()
                ->route('admin.cms.home_page.top_section')
                ->with('notify-success', 'Updated successfully');
        } catch (\Throwable $e) {
            return back()->with('notify-error', $e->getMessage());
        }
    }

    // Category Section
    public function categorySection()
    {
        $data = CMS::firstOrNew([
            'page' => Page::HomePage,
            'section' => Section::CategorySection,
        ]);
        $data->main_text = $data->main_text ?? '';
        $data->v1 = $data->v1 ?? [];
        $data->v2 = $data->v2 ?? [];
        $data->v3 = $data->v3 ?? [];

        return view('backend.layout.cms.home_page.category_section', compact('data'));
    }

    public function categorySectionUpdate(HomePageCategorySectionRequest $request)
    {
        try {

            $data = CMS::firstOrNew([
                'page' => Page::HomePage,
                'section' => Section::CategorySection,
            ]);

            $oldV1 = $data->v1 ?? [];
            $oldV2 = $data->v2 ?? [];
            $oldV3 = $data->v3 ?? [];

            $blocks = [&$oldV1, &$oldV2, &$oldV3];
            $newData = [];

            for ($i = 0; $i < 3; $i++) {

                $title = $request->title[$i] ?? null;

                if (!$title) {
                    $newData[$i] = [];
                    continue;
                }

                $oldImagePath = $blocks[$i]['image'] ?? null;
                $imagePath = $oldImagePath;

                if (!empty($request->file('image')[$i]) && $request->file('image')[$i]->isValid()) {

                    if ($oldImagePath && file_exists(public_path($oldImagePath))) {
                        unlink(public_path($oldImagePath));
                    }

                    $file = $request->file('image')[$i];

                    $name = 'category-v' . ($i + 1) . '-' . Str::slug($title) . '-' . uniqid();

                    $imagePath = Helper::fileUpload($file, 'images', $name);
                }

                $newData[$i] = [
                    'title' => $title,
                    'sub_title' => $request->sub_title[$i] ?? null,
                    'image' => $imagePath,
                    'button_text' => $request->button_text[$i] ?? null,
                    'button_link' => $request->button_link[$i] ?? null,
                ];
            }
            $data->main_text = $request->main_text;

            $data->v1 = $newData[0] ?? [];
            $data->v2 = $newData[1] ?? [];
            $data->v3 = $newData[2] ?? [];

            $data->status = $request->status ?? 'active';
            $data->save();

            return back()->with('notify-success', 'Category section updated successfully');
        } catch (Exception $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with('notify-error', $e->getMessage());
        }
    }
    // helper function

    public function getProductsByCategory(Request $request)
    {
        $categoryId    = $request->input('category_id');
        $subcategoryId = $request->input('subcategory_id');

        $query = Product::with('category')
            ->where('status', 'active');

        if ($subcategoryId) {
            $query->where('sub_category_id', $subcategoryId);
        } elseif ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->select('id', 'name', 'price', 'thumbnail', 'brand_name', 'category_id', 'sub_category_id')
            ->latest()
            ->get()
            ->map(function ($p) {
                return [
                    'id'         => $p->id,
                    'brand_name' => $p->brand_name ?? '',
                    'title'      => $p->name,
                    'price'      => $p->price,
                    'image'      => $p->thumbnail ?? null,
                ];
            });

        return response()->json(['products' => $products]);
    }
    // Men Collection Section
    public function menCollectionSection()
    {
        $data = CMS::firstOrNew([
            'page'    => Page::HomePage,
            'section' => Section::MenCollectionSection,
        ]);

        return view('backend.layout.cms.home_page.men_collection_section', compact('data'));
    }

    public function menCollectionSectionUpdate(HomePageMenCollectionSectionRequest $request)
    {
        try {
            $data = CMS::firstOrNew([
                'page' => Page::HomePage,
                'section' => Section::MenCollectionSection,
            ]);

            // save
            $data->title = $request->title;
            $data->sub_title = $request->sub_title;
            $data->button_text = $request->button_text;
            // $data->link_url = $request->button_link;

            $data->status = 'active';
            $data->save();

            return back()->with('notify-success', 'Men collection section updated successfully');
        } catch (Exception $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with('notify-error', $e->getMessage());
        }
    }

    // Women Collection Section
    public function WomenCollectionSection()
    {
        $data = CMS::firstOrNew([
            'page' => Page::HomePage,
            'section' => Section::WomenCollectionSection,
        ]);

        return view('backend.layout.cms.home_page.women_collection_section', compact('data'));
    }

    public function WomenCollectionSectionUpdate(HomePageWomenCollectionSectionRequest $request)
    {
        try {
            $data = CMS::firstOrNew([
                'page' => Page::HomePage,
                'section' => Section::WomenCollectionSection,
            ]);

            $data->title = $request->title;
            $data->sub_title = $request->sub_title;
            $data->button_text = $request->button_text;

            $data->status = 'active';
            $data->save();

            return back()->with('notify-success', 'Women collection section updated successfully');
        } catch (Exception $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with('notify-error', $e->getMessage());
        }
    }
    // Watches
    public function watchesSection()
    {
        $data = CMS::firstOrNew([
            'page' => Page::HomePage,
            'section' => Section::WatchSection,
        ]);

        return view('backend.layout.cms.home_page.watches_section', compact('data'));
    }

    public function watchesSectionUpdate(HomePageWatchesSectionRequest $request)
    {
        try {
            $data = CMS::firstOrNew([
                'page' => Page::HomePage,
                'section' => Section::WatchSection,
            ]);

            $data->title = $request->title;
            $data->sub_title = $request->sub_title;
            $data->button_text = $request->button_text;
            // $data->link_url = $request->link_url;

            $data->status = 'active';
            $data->save();

            return back()->with('notify-success', 'Watches section updated successfully');
        } catch (Exception $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with('notify-error', $e->getMessage());
        }
    }
    // High Tech
    public function HighTechSection()
    {
        $data = CMS::firstOrNew([
            'page' => Page::HomePage,
            'section' => Section::HighTechSection,
        ]);

        return view('backend.layout.cms.home_page.high_tech_section', compact('data'));
    }

    public function HighTechSectionUpdate(HomePageHighTechSectionRequest $request)
    {
        try {
            $data = CMS::firstOrNew([
                'page' => Page::HomePage,
                'section' => Section::HighTechSection,
            ]);

            $data->title = $request->title;
            $data->sub_title = $request->sub_title;
            $data->button_text = $request->button_text;
            // $data->link_url = $request->link_url;

            $data->status = 'active';
            $data->save();

            return back()->with('notify-success', 'High Tech section updated successfully');
        } catch (Exception $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with('notify-error', $e->getMessage());
        }
    }
}
