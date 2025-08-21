
const renderSectionItem = () => {
    return `
        <div className="card shadow-sm mb-5">
            <div className="card-header p-3">
                <div className="row">
                    <div className="col">
                        <label htmlFor="">Tên section</label>
                        <input type={\`text\`} className={\`form-control mt-3\`} name={\`title\`}/>
                    </div>
                    <div className="col">
                        <label htmlFor="">Link xem tất cả</label>
                        <input type={\`text\`} className={\`form-control mt-3\`} name={\`viewAllLink\`} />
                    </div>
                    <div className="col">
                        <label htmlFor="">Text Btn</label>
                        <input type={\`text\`} className={\`form-control mt-3\`} name={\`viewAllTxtBtn\`} />
                    </div>
                    <div className="col">
                        <label htmlFor="">Kiểu section</label>
                    </div>
                </div>
                <div className="card-toolbar">
                    <button className={\`btn btn-danger\`}><i className={\`fa fa-trash\`}></i></button>
                </div>
            </div>
            <div id="section-content" className="card-body">

            </div>
        </div>
    `;
}

$('#add-section').click(function() {
    $('#section-list').append(renderSectionItem());
});
