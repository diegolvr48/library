<template>
    <div class="app-container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">The Library</a>
            <div class="collapse navbar-collapse" v-if="$auth.check()">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'book' }">Books</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'category' }">Category</router-link>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link" @click.prevent="$auth.logout({redirect: '/login'})">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="content-page">
            <router-view></router-view>
        </div>
    </div>
</template>
