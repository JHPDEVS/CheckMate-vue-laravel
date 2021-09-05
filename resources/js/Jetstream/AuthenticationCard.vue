<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <slot name="logo" />
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <slot />
        </div>
       <div class=" sm:max-w-md mt-6 px-6 py-4 overflow-hidden sm:rounded-lg">
           <a href="http://www.trustlogo.com/" target="_blank">
            <img alt="..." class="hover:opacity-50" src="/img/positivessl_trust_seal_md_167x42.png">
           </a>
        </div>
    </div>
</template>