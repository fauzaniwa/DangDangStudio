<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-brandPrimary text-white transform -translate-x-full transition-transform duration-300 ease-in-out md:relative md:translate-x-0 flex flex-col shadow-2xl">

    <div class="p-6 border-b border-white/10">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-tr from-brandAccent to-brandGold rounded-xl flex items-center justify-center font-bold text-white shadow-lg">DS</div>
            <div>
                <h1 class="text-lg font-bold leading-none">DANGDANG</h1>
                <span class="text-[10px] text-brandGold font-semibold tracking-tighter uppercase italic">Studio Management</span>
            </div>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto p-4 space-y-1 custom-scrollbar">

        <a href="dashboard.php" class="flex items-center p-3 rounded-xl bg-brandAccent text-white shadow-md mb-6 hover:scale-[1.02] transition-transform">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
            </svg>
            <span class="ml-3 font-semibold text-sm">Dashboard Overview</span>
        </a>

        <div class="pb-4">
            <p class="px-4 text-[11px] text-gray-400 font-bold uppercase tracking-widest mb-2">Project & Finance</p>
            <div class="space-y-1">
                <?php
                $projectMenus = [
                    'Project Manager' => ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'link' => 'project_manager.php'],
                    'Project Timeline' => ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'link' => 'project_timeline.php'],
                    'Client List' => ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'link' => 'client_list.php'],
                    'Financial Report' => ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'link' => 'financial_report.php'],
                ];
                foreach ($projectMenus as $title => $data): ?>
                    <a href="<?php echo $data['link']; ?>" class="flex items-center p-3 rounded-xl text-gray-300 hover:bg-white/5 hover:text-brandGold transition group">
                        <svg class="w-5 h-5 group-hover:text-brandGold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo $data['icon']; ?>"></path>
                        </svg>
                        <span class="ml-3 text-sm font-medium"><?php echo $title; ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="pb-4 border-t border-white/5 pt-4">
            <p class="px-4 text-[11px] text-gray-400 font-bold uppercase tracking-widest mb-2">Content Management</p>
            <div class="space-y-1">
                <?php
                $contentMenus = [
                    'Game Info' => ['icon' => 'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z', 'link' => 'games.php'],
                    'Team Info' => ['icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'link' => 'team.php'],
                    'Article / Blog' => ['icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z', 'link' => 'articles.php'],
                    'Social Media Plan' => ['icon' => 'M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z', 'link' => 'social_media_plan.php'],
                    'Testimonial' => ['icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z', 'link' => 'testimonials.php']
                ];
                foreach ($contentMenus as $title => $data): ?>
                    <a href="<?php echo $data['link']; ?>" class="flex items-center p-3 rounded-xl text-gray-300 hover:bg-white/5 hover:text-brandTeal transition group">
                        <svg class="w-5 h-5 group-hover:text-brandTeal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo $data['icon']; ?>"></path>
                        </svg>
                        <span class="ml-3 text-sm font-medium"><?php echo $title; ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="pb-4 border-t border-white/5 pt-4">
            <p class="px-4 text-[11px] text-gray-400 font-bold uppercase tracking-widest mb-2">Systems</p>
            <div class="space-y-1">
                <a href="admin_manager.php" class="flex items-center p-3 rounded-xl text-gray-300 hover:bg-white/5 hover:text-white transition group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="ml-3 text-sm font-medium">Admin Manager</span>
                </a>
                <a href="admin_logs.php" class="flex items-center p-3 rounded-xl text-gray-300 hover:bg-white/5 hover:text-white transition group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09a10.116 10.116 0 001.283-3.562L7 11.122V11m0 0V9.878m0 1.244L4.782 11M12 11V9a4 4 0 118 0v1a4 4 0 01-4 4h-4z"></path>
                    </svg>
                    <span class="ml-3 text-sm font-medium">Admin Logs</span>
                </a>
            </div>
        </div>

        <div class="pt-4 border-t border-white/5">
            <a href="logout.php" class="flex items-center p-3 rounded-xl text-red-400 hover:bg-red-500/10 transition group">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span class="ml-3 text-sm font-bold uppercase tracking-wider">Logout</span>
            </a>
        </div>
    </nav>
</aside>