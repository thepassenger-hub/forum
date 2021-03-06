class Path {
    constructor(){
        this.subpath = '';
        this.breadcrumbs = this.setBreadcrumbs(this.subpath);
    }

    /**
     * Update the current path and add a new field at the breadcrumb.
     * @param {string} newSubPath
     */
    update(newSubPath) {
        this.subpath = newSubPath;
        this.breadcrumbs = this.setBreadcrumbs(this.subpath);
    }
    
    /**
     * Set the sub paths from an array.
     * @param {array} subpaths
     * @return {array}
     */
    setSubPaths(subpaths) {
        let subpath = subpaths.slice(-1)[0] === '' ? ['/'] : subpaths;
        let out = [];
        subpath.forEach(path => {
            if (out.slice(-1)[0] !== undefined && out.slice(-1)[0] !== '/') {
                out.push(out.slice(-1)[0] + '/' + path);
            } else if (out.slice(-1)[0] !== undefined) {
                out.push(out.slice(-1)[0] + path);                            
            }
                else {
                out.push('/');
            };
        });
        return out;
    }

    /**
     * Prettify the sub routes names.
     * @param {array} subnames
     */
    prettifySubNames(subnames) {
        let out = []
        subnames.forEach(subname => {
            let prettySub = subname === '' ? 'Home' : subname;
            out.push(prettySub);
        })

        return out;
    }

    /**
     * Set the breadcrumb from an uri.
     * @param {string} uri
     * @return {array} Array of objects containing name and path of the route.
     */
    setBreadcrumbs(uri){
        let subpaths = uri.split('/');
        if (subpaths.slice(-1)[0] === '') return [{name: 'Home', path: '/'}];
        let prettyNames = this.prettifySubNames(subpaths);
        let allSubPaths = this.setSubPaths(subpaths);
        let out = []
        for (let i=0; i< prettyNames.length; i++){
            out.push({name: prettyNames[i], path: allSubPaths[i]});
        }

        return out;
    }

}

export default Path;
// subpath: /php/1/
// prettyName: channel.Title
// subpaths: / /php /php/1
// prettySubNames: [Home, PHP, Titolo]
// breadcrumb: [{name: Home, path: /},]

